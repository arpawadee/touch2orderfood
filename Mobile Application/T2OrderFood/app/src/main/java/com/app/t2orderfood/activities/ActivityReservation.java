package com.app.t2orderfood.activities;

import android.app.DatePickerDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.app.TimePickerDialog;
import android.content.Intent;
import android.content.res.Configuration;
import android.database.SQLException;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.DialogFragment;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.text.format.DateFormat;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.ScrollView;
import android.widget.TextView;
import android.widget.TimePicker;
import android.widget.Toast;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.utilities.DBHelper;
import com.app.t2orderfood.utilities.Utils;

import org.apache.http.HttpResponse;
import org.apache.http.NameValuePair;
import org.apache.http.client.HttpClient;
import org.apache.http.client.entity.UrlEncodedFormEntity;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpPost;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.message.BasicNameValuePair;
import org.apache.http.params.HttpConnectionParams;
import org.apache.http.protocol.HTTP;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.MalformedURLException;
import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.List;
import java.util.Locale;

public class ActivityReservation extends AppCompatActivity {

    Button btnSend;

    EditText edtName;
    EditText edtTableNo;
   // EditText edtPhone;
  //  EditText edtEmail;
    EditText edtTotalPrice;
    EditText edtOrderList;
    EditText edtComment;
    static TextView dateText, timeText;

    static Button btnDate;
    static Button btnTime;

    ScrollView sclDetail;
    ProgressBar progressBar;
    TextView txtAlert;

    public static DBHelper dbhelper;
    ArrayList<ArrayList<Object>> data;

    String Name, TableNo, Date, Time, Date_Time;
    String OrderList = "";
    String TotalPrice = "";
    String Tprice;
    String Comment = "";

    private static int mYear;
    private static int mMonth;
    private static int mDay;
    private static int mHour;
    private static int mMinute;

    // declare static variables to store tax and currency data
    public static double Tax;
    public static String Currency;

    public static final String TIME_DIALOG_ID = "timePicker";
    public static final String DATE_DIALOG_ID = "datePicker";

    String Result;
    String TaxCurrencyAPI;
    int IOConnect = 0;


    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        if (Config.ENABLE_RTL_MODE) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
                setContentView(R.layout.activity_reservation_rtl);
                getWindow().getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
            }
        } else {
            setContentView(R.layout.activity_reservation);
            Log.d("Log", "Working in Normal Mode, RTL Mode is Disabled");
        }

        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        final android.support.v7.app.ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle(R.string.title_checkout);
        }

        edtName = (EditText) findViewById(R.id.edtName);
        edtTableNo = (EditText) findViewById(R.id.edtTableNo);
     //   edtEmail = (EditText) findViewById(R.id.edtEmail);
    //    btnDate = (Button) findViewById(R.id.btnDate);
     //   btnTime = (Button) findViewById(R.id.btnTime);
     //   edtPhone = (EditText) findViewById(R.id.edtPhone);
        edtOrderList = (EditText) findViewById(R.id.edtOrderList);
        edtTotalPrice = (EditText) findViewById(R.id.edtTotalPrice);
        edtComment = (EditText) findViewById(R.id.edtComment);
        btnSend = (Button) findViewById(R.id.btnSend);
        sclDetail = (ScrollView) findViewById(R.id.sclDetail);
        progressBar = (ProgressBar) findViewById(R.id.prgLoading);
        txtAlert = (TextView) findViewById(R.id.txtAlert);

        dateText = (TextView) findViewById(R.id.dateText);
        timeText = (TextView) findViewById(R.id.timeText);

        SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
        String date = dateFormat.format(Calendar.getInstance().getTime());
        dateText.setText(date);


        SimpleDateFormat timeFormat = new SimpleDateFormat("HH:mm");
        String time = timeFormat.format(Calendar.getInstance().getTime());
        timeText.setText(time);

        TaxCurrencyAPI = Config.ADMIN_PANEL_URL + "/api/get-tax-and-currency.php" + "?accesskey=" + Utils.ACCESS_KEY;

        dbhelper = new DBHelper(this);
        try {
            dbhelper.openDataBase();
        } catch (SQLException sqle) {
            throw sqle;
        }

        new getTaxCurrency().execute();

//        btnDate.setOnClickListener(new OnClickListener() {
//
//            public void onClick(View v) {
//                DialogFragment newFragment = new DatePickerFragment();
//                newFragment.show(getSupportFragmentManager(), DATE_DIALOG_ID);
//            }
//        });

        dateText.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                DialogFragment newFragment = new DatePickerFragment();
                newFragment.show(getSupportFragmentManager(), DATE_DIALOG_ID);
            }
        });

//        btnTime.setOnClickListener(new OnClickListener() {
//
//            public void onClick(View v) {
//                DialogFragment newFragment = new TimePickerFragment();
//                newFragment.show(getSupportFragmentManager(), TIME_DIALOG_ID);
//            }
//        });

        btnSend.setOnClickListener(new OnClickListener() {

            public void onClick(View arg0) {

                Name = edtName.getText().toString();
                TableNo = edtTableNo.getText().toString();
                Date = dateText.getText().toString();
                Time = timeText.getText().toString();
                Comment = edtComment.getText().toString();
                Date_Time = Date + " " + Time;
                if (
                        //Name.equalsIgnoreCase("") ||
                        TableNo.equalsIgnoreCase("") ||
                        //Date.equalsIgnoreCase(getString(R.string.checkout_set_date)) ||
                        //Time.equalsIgnoreCase(getString(R.string.checkout_set_time)) ||
                        Date.equalsIgnoreCase("") ||
                        Time.equalsIgnoreCase("")
                        ) {
                    Toast.makeText(ActivityReservation.this, R.string.form_alert, Toast.LENGTH_SHORT).show();

                } else if ((data.size() == 0)) {
                    Toast.makeText(ActivityReservation.this, R.string.order_alert, Toast.LENGTH_SHORT).show();

                } else {
                    new sendData().execute();

                }
            }
        });
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {

            case android.R.id.home:
                this.finish();
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }

    public static class DatePickerFragment extends DialogFragment implements DatePickerDialog.OnDateSetListener {

        @Override
        public Dialog onCreateDialog(Bundle savedInstanceState) {
            final Calendar c = Calendar.getInstance();
            int year = c.get(Calendar.YEAR);
            int month = c.get(Calendar.MONTH);
            int day = c.get(Calendar.DAY_OF_MONTH);

            return new DatePickerDialog(getActivity(), this, year, month, day);
        }

        public void onDateSet(DatePicker view, int year, int month, int day) {
            mYear = year;
            mMonth = month;
            mDay = day;

//            btnDate.setText(new StringBuilder()
//                    .append(mYear).append("/")
//                    .append(mMonth + 1).append("/")
//                    .append(mDay).append(" "));

            dateText.setText(new StringBuilder()
                    .append(mYear).append("-")
                    .append(mMonth + 1).append("-")
                    .append(mDay).append(" "));

        }
    }

    public static class TimePickerFragment extends DialogFragment
            implements TimePickerDialog.OnTimeSetListener {

        @Override
        public Dialog onCreateDialog(Bundle savedInstanceState) {
            final Calendar c = Calendar.getInstance();
            int hour = c.get(Calendar.HOUR_OF_DAY);
            int minute = c.get(Calendar.MINUTE);

            return new TimePickerDialog(getActivity(), this, hour, minute, DateFormat.is24HourFormat(getActivity()));
        }

        public void onTimeSet(TimePicker view, int hourOfDay, int minute) {
            mHour = hourOfDay;
            mMinute = minute;

//            btnTime.setText(new StringBuilder()
//                    .append(pad(mHour)).append(":")
//                    .append(pad(mMinute)).append(":")
//                    .append("00"));

            timeText.setText(new StringBuilder()
                    .append(pad(mHour)).append(":")
                    .append(pad(mMinute)).append(":")
                    .append("00"));
        }
    }

    public class getTaxCurrency extends AsyncTask<Void, Void, Void> {

        getTaxCurrency() {
            if (!progressBar.isShown()) {
                progressBar.setVisibility(View.VISIBLE);
                txtAlert.setVisibility(View.GONE);
            }
        }

        @Override
        protected Void doInBackground(Void... arg0) {
            parseJSONDataTax();
            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            progressBar.setVisibility(View.GONE);
            if (IOConnect == 0) {
                new getDataTask().execute();
            } else {
                txtAlert.setVisibility(View.VISIBLE);
            }
        }
    }

    public void parseJSONDataTax() {

        try {

            HttpClient client = new DefaultHttpClient();
            HttpConnectionParams.setConnectionTimeout(client.getParams(), 15000);
            HttpConnectionParams.setSoTimeout(client.getParams(), 15000);
            HttpUriRequest request = new HttpGet(TaxCurrencyAPI);
            HttpResponse response = client.execute(request);
            InputStream atomInputStream = response.getEntity().getContent();


            BufferedReader in = new BufferedReader(new InputStreamReader(atomInputStream));

            String line;
            String str = "";
            while ((line = in.readLine()) != null) {
                str += line;
            }

            JSONObject json = new JSONObject(str);
            JSONArray data = json.getJSONArray("data");


            JSONObject object_tax = data.getJSONObject(0);
            JSONObject tax = object_tax.getJSONObject("tax_n_currency");

            Tax = Double.parseDouble(tax.getString("Value"));

            JSONObject object_currency = data.getJSONObject(1);
            JSONObject currency = object_currency.getJSONObject("tax_n_currency");

            Currency = currency.getString("Value");

        } catch (MalformedURLException e) {
            e.printStackTrace();
        } catch (IOException e) {
            IOConnect = 1;
            e.printStackTrace();
        } catch (JSONException e) {
            e.printStackTrace();
        }
    }

    public class getDataTask extends AsyncTask<Void, Void, Void> {

        @Override
        protected Void doInBackground(Void... arg0) {
            getDataFromDatabase();
            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            progressBar.setVisibility(View.GONE);
            sclDetail.setVisibility(View.VISIBLE);

        }
    }

    public class sendData extends AsyncTask<Void, Void, Void> {

        ProgressDialog dialog;

        @Override
        protected void onPreExecute() {
            dialog = ProgressDialog.show(ActivityReservation.this, "", getString(R.string.sending_alert), true);
           // Toast.makeText(ActivityReservation.this, R.string.ok_alert, Toast.LENGTH_SHORT).show();
            Intent i = new Intent(ActivityReservation.this, ActivityConfirmMessage.class);
            startActivity(i);
            finish();

        }

        @Override
        protected Void doInBackground(Void... params) {

            Result = getRequest(Name, TableNo, Date_Time, OrderList, Tprice, Comment);
            return null;
        }

        @Override
        protected void onPostExecute(Void result) {
            dialog.dismiss();
            resultAlert(Result);
        }
    }

    public void resultAlert(String HasilProses) {
        if (HasilProses.trim().equalsIgnoreCase("OK")) {
            Toast.makeText(ActivityReservation.this, R.string.ok_alert, Toast.LENGTH_SHORT).show();
            Intent i = new Intent(ActivityReservation.this, ActivityConfirmMessage.class);
            startActivity(i);
            finish();
            Log.d("HasilOk", HasilProses);
        } else if (HasilProses.trim().equalsIgnoreCase("Failed")) {
            Toast.makeText(ActivityReservation.this, R.string.failed_alert, Toast.LENGTH_SHORT).show();
        } else {
            Log.d("HasilProses", HasilProses);
        }
    }

    public String getRequest(
            String name,
            String table_no,
            String date_time,
            String order_list,
            String total_price,
            String comment) {
        String result = "";

        HttpClient client = new DefaultHttpClient();
        HttpPost request = new HttpPost(Config.ADMIN_PANEL_URL + "/api/add-order.php");

        try {
            List<NameValuePair> nameValuePairs = new ArrayList<NameValuePair>(6);
            nameValuePairs.add(new BasicNameValuePair("name", name));
            nameValuePairs.add(new BasicNameValuePair("table_no", table_no));
            nameValuePairs.add(new BasicNameValuePair("date_time", date_time));
            nameValuePairs.add(new BasicNameValuePair("order_list", order_list));
            nameValuePairs.add(new BasicNameValuePair("total_price", total_price));
            nameValuePairs.add(new BasicNameValuePair("comment", comment));
            request.setEntity(new UrlEncodedFormEntity(nameValuePairs, HTTP.UTF_8));
            HttpResponse response = client.execute(request);
            result = request(response);
        } catch (Exception ex) {
            result = "Unable to connect.";
        }
        return result;
    }

    public static String request(HttpResponse response) {
        String result = "";
        try {
            InputStream in = response.getEntity().getContent();
            BufferedReader reader = new BufferedReader(new InputStreamReader(in));
            StringBuilder str = new StringBuilder();
            String line = null;
            while ((line = reader.readLine()) != null) {
                str.append(line + "\n");
            }
            in.close();
            result = str.toString();
        } catch (Exception ex) {
            result = "Error";
        }
        return result;
    }

    public void getDataFromDatabase() {

        data = dbhelper.getAllData();

        double Order_price = 0;
        double Total_price = 0;
        double tax = 0;

        for (int i = 0; i < data.size(); i++) {
            ArrayList<Object> row = data.get(i);

            String Menu_name = row.get(1).toString();
            String Quantity = row.get(2).toString();

//            NumberFormat numberFormat = NumberFormat.getNumberInstance(Locale.ENGLISH);
//            DecimalFormat formatData = (DecimalFormat)numberFormat;
//            formatData.applyPattern("#.##");

            DecimalFormat formatter2 = new DecimalFormat("0.00");


 //           double Sub_total_price = Double.parseDouble(formatter2.format(Double.parseDouble(row.get(3).toString())));

             double Sub_total_price = Double.parseDouble(row.get(3).toString());


            Order_price += Sub_total_price;
            Tprice = Double.toString(Order_price);

            String SubTotal = formatter2.format(Sub_total_price);


            OrderList += (Quantity + "  " + Menu_name + "   " + "฿" + SubTotal + " " + ",\n");
        }

        if (OrderList.equalsIgnoreCase("")) {
            OrderList += getString(R.string.no_order_menu);
        }

//        NumberFormat numberFormat = NumberFormat.getNumberInstance(Locale.ENGLISH);
//        DecimalFormat formatData = (DecimalFormat)numberFormat;
//        formatData.applyPattern("#.##");
//        tax = Double.parseDouble(formatData.format(Order_price * (Tax / 100)));
//        Total_price = Double.parseDouble(formatData.format(Order_price + tax));

       // String _Order_price = String.format(Locale.GERMAN, "%1$,.0f", Order_price);
        DecimalFormat formatter = new DecimalFormat("0.00");
        String _Order_price =  formatter.format(Order_price);
//        String _tax = String.format(Locale.GERMAN, "%1$,.0f", tax);
//        String _Total_price = String.format(Locale.GERMAN, "%1$,.0f", Total_price);

       // OrderList +=  "\n" + getResources().getString(R.string.txt_order) + "   "+ _Order_price + " " + ActivityMenuList.Currency ;
//                "\n" + getResources().getString(R.string.txt_tax) + " " + Tax + "% : " + _tax + " " + ActivityMenuList.Currency +
//                "\n" + getResources().getString(R.string.txt_total) + _Total_price + " " + ActivityMenuList.Currency;

        TotalPrice += getResources().getString(R.string.txt_order) + "   "+ _Order_price + " " + ActivityMenuList.Currency ;

        edtOrderList.setText(OrderList);
        edtTotalPrice.setText(TotalPrice);
    }

    private static String pad(int c) {
        if (c >= 10) {
            return String.valueOf(c);
        } else {
            return "0" + String.valueOf(c);
        }
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        //dbhelper.close();
        finish();
    }

    @Override
    public void onConfigurationChanged(final Configuration newConfig) {
        super.onConfigurationChanged(newConfig);
    }
}