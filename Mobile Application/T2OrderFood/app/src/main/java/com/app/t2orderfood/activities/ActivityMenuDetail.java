package com.app.t2orderfood.activities;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.database.SQLException;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.BitmapDrawable;
import android.media.MediaScannerConnection;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.support.design.widget.AppBarLayout;
import android.support.design.widget.CollapsingToolbarLayout;
import android.support.design.widget.CoordinatorLayout;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.graphics.Palette;
import android.support.v7.widget.Toolbar;
import android.text.InputFilter;
import android.text.InputType;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.utilities.DBHelper;
import com.app.t2orderfood.utilities.Utils;
import com.squareup.picasso.Callback;
import com.squareup.picasso.Picasso;

import org.apache.http.HttpResponse;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.client.methods.HttpUriRequest;
import org.apache.http.impl.client.DefaultHttpClient;
import org.apache.http.params.HttpConnectionParams;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.text.DecimalFormat;
import java.text.NumberFormat;
import java.util.Locale;

public class ActivityMenuDetail extends AppCompatActivity {

    Button btnAddToCart;
    ImageView imageView,plus,minus;
    TextView txtName, txtPrice, txtPeople, txtStatus, number ;
    CoordinatorLayout coordinatorLayout;
    ProgressBar progressBar;
    TextView txtAlert;
    WebView txtDescription;
    public static DBHelper dbhelper;
    String Menu_image, Menu_name, menu_status, Menu_description;
    double Menu_price;
    double pricee;
    int serve_for;
    int counter = 1;
    long Menu_ID;
    String MenuDetailAPI;
    int IOConnect = 0;
    final Context context = this;
    private CollapsingToolbarLayout collapsingToolbarLayout;
    private AppBarLayout appBarLayout;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_detail);

        if (Config.ENABLE_RTL_MODE) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
                getWindow().getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
            }
        } else {
            Log.d("Log", "Working in Normal Mode, RTL Mode is Disabled");
        }

        imageView = (ImageView) findViewById(R.id.imgPreview);
        txtName = (TextView) findViewById(R.id.txtName);
        txtPrice = (TextView) findViewById(R.id.txtPrice);
        txtPeople = (TextView) findViewById(R.id.txtPeople);
        txtStatus = (TextView) findViewById(R.id.txt_status);
        txtDescription = (WebView) findViewById(R.id.txtDescription);
        coordinatorLayout = (CoordinatorLayout) findViewById(R.id.main_content);

        progressBar = (ProgressBar) findViewById(R.id.prgLoading);
        txtAlert = (TextView) findViewById(R.id.txtAlert);

        plus = (ImageView) findViewById(R.id.plus);
        minus = (ImageView) findViewById(R.id.minus);
        number = (TextView) findViewById(R.id.number);

        btnAddToCart = (Button) findViewById(R.id.btnAddToCart);


        FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.btnAdd);

        btnAddToCart.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (menu_status != null && menu_status.equals("Sold Out")) {
                    Toast.makeText(getApplicationContext(), "this menu is sold out!", Toast.LENGTH_SHORT).show();
                } else {
                    inputDialog();
                }
            }
        });


        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(ActivityMenuDetail.this,ActivityCart.class);
                startActivity(intent);
            }
        });

//        com.github.clans.fab.FloatingActionButton fab2 = (com.github.clans.fab.FloatingActionButton) findViewById(R.id.cart);
//        fab2.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                startActivity(new Intent(getApplicationContext(), ActivityCart.class));
//            }
//        });

//        com.github.clans.fab.FloatingActionButton fab3 = (com.github.clans.fab.FloatingActionButton) findViewById(R.id.checkout);
//        fab3.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                startActivity(new Intent(getApplicationContext(), ActivityReservation.class));
//            }
//        });
//
//        com.github.clans.fab.FloatingActionButton fab4 = (com.github.clans.fab.FloatingActionButton) findViewById(R.id.save);
//        fab4.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                (new SaveTask(ActivityMenuDetail.this)).execute(Config.ADMIN_PANEL_URL + "/" + Menu_image);
//            }
//        });

        dbhelper = new DBHelper(this);

        Intent iGet = getIntent();
        Menu_ID = iGet.getLongExtra("menu_id", 0);
        Menu_name = iGet.getStringExtra("menu_name");

        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        final android.support.v7.app.ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        }

        collapsingToolbarLayout = (CollapsingToolbarLayout) findViewById(R.id.collapsing_toolbar);
        collapsingToolbarLayout.setTitle("");
        appBarLayout = (AppBarLayout) findViewById(R.id.appbar);
        appBarLayout.setExpanded(true);

        appBarLayout.addOnOffsetChangedListener(new AppBarLayout.OnOffsetChangedListener() {
            boolean isShow = false;
            int scrollRange = -1;

            @Override
            public void onOffsetChanged(AppBarLayout appBarLayout, int verticalOffset) {
                if (scrollRange == -1) {
                    scrollRange = appBarLayout.getTotalScrollRange();
                }
                if (scrollRange + verticalOffset == 0) {
                    collapsingToolbarLayout.setTitle(Menu_name);
                    isShow = true;
                } else if (isShow) {
                    collapsingToolbarLayout.setTitle("");
                    isShow = false;
                }
            }
        });

        MenuDetailAPI = Config.ADMIN_PANEL_URL + "/api/get-menu-detail.php" + "?accesskey=" + Utils.ACCESS_KEY + "&menu_id=" + Menu_ID;

        new getDataTask().execute();


        number.setText("" + counter);


        plus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                number.setText("" + ++counter );

                DecimalFormat precision = new DecimalFormat("0.00");
                txtPrice.setText(precision.format(Menu_price*counter ) + " ฿");

            }
        });


        minus.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (counter > 1 ) {
                    number.setText("" + --counter);
                    DecimalFormat precision = new DecimalFormat("0.00");
                    txtPrice.setText(precision.format(Menu_price*counter) + " ฿");

                }
            }
        });







    }


    @Override
    protected void onRestoreInstanceState(Bundle savedInstanceState) {
        super.onRestoreInstanceState(savedInstanceState);
        counter = savedInstanceState.getInt("counter");

        number.setText("" + counter);
        txtPrice.setText("" + Menu_price);
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        outState.putInt("counter", counter);
        outState.putDouble("pricee", Menu_price);

    }
    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_detail, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {

            case android.R.id.home:
                this.finish();
                return true;

//            case R.id.buy:
//                inputDialog();
//                return true;

            case R.id.cart:
                startActivity(new Intent(getApplicationContext(), ActivityCart.class));
                return true;

            case R.id.menu:
                startActivity(new Intent(getApplicationContext(), ActivityCategory.class));
                return true;
//
//            case R.id.checkout:
//                startActivity(new Intent(getApplicationContext(), ActivityReservation.class));
//                return true;
//
//            case R.id.save:
//                (new SaveTask(ActivityMenuDetail.this)).execute(Config.ADMIN_PANEL_URL + "/" + Menu_image);
//                return true;
//
//            case R.id.share:
//                String formattedString = android.text.Html.fromHtml(Menu_description).toString();
//                Intent sendIntent = new Intent();
//                sendIntent.setAction(Intent.ACTION_SEND);
//                sendIntent.putExtra(Intent.EXTRA_TEXT, Menu_name + "\n" + formattedString + "\n" + getResources().getString(R.string.share_content) + "https://play.google.com/store/apps/details?id=" + getPackageName());
//                sendIntent.setType("text/plain");
//                startActivity(sendIntent);

            default:
                return super.onOptionsItemSelected(item);
        }

    }

    public void inputDialog() {

        try {
            dbhelper.openDataBase();
        } catch (SQLException sqle) {
            throw sqle;
        }

        if (Config.ENABLE_RTL_MODE) {

            LayoutInflater layoutInflaterAndroid = LayoutInflater.from(context);

            View mView = layoutInflaterAndroid.inflate(R.layout.input_dialog_rtl, null);

            AlertDialog.Builder alert = new AlertDialog.Builder(context);
            alert.setView(mView);

           // final EditText edtQuantity = (EditText) mView.findViewById(R.id.userInputDialog);

            alert.setCancelable(false);
//            int maxLength = 3;
//            edtQuantity.setFilters(new InputFilter[]{new InputFilter.LengthFilter(maxLength)});
//            edtQuantity.setInputType(InputType.TYPE_CLASS_NUMBER);

            alert.setPositiveButton("Add", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int whichButton) {
                  //  String temp = edtQuantity.getText().toString();
                    String t = number.getText().toString();
                    int quantity = 0;

                    if (!t.equalsIgnoreCase("")) {
                        quantity = Integer.parseInt(t);
                        Toast.makeText(getApplicationContext(), "Success add product to cart", Toast.LENGTH_SHORT).show();

                        if (dbhelper.isDataExist(Menu_ID)) {
                            dbhelper.updateData(Menu_ID, quantity, (Menu_price * quantity));
                        } else {
                            dbhelper.addData(Menu_ID, Menu_name, quantity, (Menu_price * quantity));
                        }
                    } else {
                        dialog.cancel();
                    }
                }
            });

            alert.setNegativeButton("Cancel",
                    new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            dialog.cancel();
                        }
                    });

            AlertDialog alertDialog = alert.create();
            alertDialog.show();

        } else {

            LayoutInflater layoutInflaterAndroid = LayoutInflater.from(context);

            View mView = layoutInflaterAndroid.inflate(R.layout.input_dialog, null);

            AlertDialog.Builder alert = new AlertDialog.Builder(context);
            alert.setView(mView);

           // final EditText edtQuantity = (EditText) mView.findViewById(R.id.userInputDialog);
            alert.setCancelable(false);
//            int maxLength = 3;
//            edtQuantity.setFilters(new InputFilter[]{new InputFilter.LengthFilter(maxLength)});
//            edtQuantity.setInputType(InputType.TYPE_CLASS_NUMBER);

            alert.setPositiveButton("Add", new DialogInterface.OnClickListener() {
                public void onClick(DialogInterface dialog, int whichButton) {
                //    String temp = edtQuantity.getText().toString();
                    String t = number.getText().toString();
                    int quantity = 0;

                    if (!t.equalsIgnoreCase("")) {
                        quantity = Integer.parseInt(t);
                        Toast.makeText(getApplicationContext(), "Success add product to cart", Toast.LENGTH_SHORT).show();

                        if (dbhelper.isDataExist(Menu_ID)) {
                            dbhelper.updateData(Menu_ID, quantity, (Menu_price * quantity));
                        } else {
                            dbhelper.addData(Menu_ID, Menu_name, quantity, (Menu_price * quantity));
                        }
                    } else {
                        dialog.cancel();
                    }
                }
            });

            alert.setNegativeButton("Cancel",
                    new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            dialog.cancel();
                        }
                    });

            AlertDialog alertDialog = alert.create();
            alertDialog.show();

        }

    }

    public class getDataTask extends AsyncTask<Void, Void, Void> {

        getDataTask() {
            if (!progressBar.isShown()) {
                progressBar.setVisibility(View.VISIBLE);
                txtAlert.setVisibility(View.GONE);
            }
        }

        @Override
        protected Void doInBackground(Void... arg0) {
            parseJSONData();
            return null;
        }

        @Override
        protected void onPostExecute(Void result) {

            if (Config.ENABLE_RTL_MODE) {

                progressBar.setVisibility(View.GONE);
                if ((Menu_name != null) && IOConnect == 0) {
                    coordinatorLayout.setVisibility(View.VISIBLE);

                    Picasso.with(getApplicationContext()).load(Config.ADMIN_PANEL_URL + "/" + Menu_image).placeholder(R.drawable.ic_loading).into(imageView, new Callback() {
                        @Override
                        public void onSuccess() {
                            Bitmap bitmap = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
                            Palette.from(bitmap).generate(new Palette.PaletteAsyncListener() {
                                @Override
                                public void onGenerated(Palette palette) {
                                }
                            });
                        }

                        @Override
                        public void onError() {

                        }
                    });

                    txtName.setText(Menu_name);
                    //txtPrice.setText(Menu_price + " " + ActivityMenuList.Currency);
                    DecimalFormat precision = new DecimalFormat("0.00");
                    txtPrice.setText(precision.format(Menu_price) + " ฿");
                    txtPeople.setText(serve_for + " " + getResources().getString(R.string.people));

                    if (menu_status != null && menu_status.equals("Available")) {
                        txtStatus.setText(getResources().getString(R.string.available));
                        txtStatus.setBackgroundResource(R.color.available);
                    } else {
                        txtStatus.setText(getResources().getString(R.string.sold));
                        txtStatus.setBackgroundResource(R.color.sold);
                    }

                    txtDescription.setBackgroundColor(Color.parseColor("#ffffff"));
                    txtDescription.setFocusableInTouchMode(false);
                    txtDescription.setFocusable(false);
                    txtDescription.getSettings().setDefaultTextEncodingName("UTF-8");

                    WebSettings webSettings = txtDescription.getSettings();
                    Resources res = getResources();
                    int fontSize = res.getInteger(R.integer.font_size);
                    webSettings.setDefaultFontSize(fontSize);
                    webSettings.setJavaScriptEnabled(true);

                    String mimeType = "text/html; charset=UTF-8";
                    String encoding = "utf-8";
                    String htmlText = Menu_description;

                    String text = "<html dir='rtl'><head>"
                            + "<style type=\"text/css\">body{color: #525252;}"
                            + "</style></head>"
                            + "<body>"
                            + htmlText
                            + "</body></html>";

                    txtDescription.loadData(text, mimeType, encoding);

                } else {
                    txtAlert.setVisibility(View.VISIBLE);
                }

            } else {

                progressBar.setVisibility(View.GONE);
                if ((Menu_name != null) && IOConnect == 0) {
                    coordinatorLayout.setVisibility(View.VISIBLE);

                    Picasso.with(getApplicationContext()).load(Config.ADMIN_PANEL_URL + "/" + Menu_image).placeholder(R.drawable.ic_loading).into(imageView, new Callback() {
                        @Override
                        public void onSuccess() {
                            Bitmap bitmap = ((BitmapDrawable) imageView.getDrawable()).getBitmap();
                            Palette.from(bitmap).generate(new Palette.PaletteAsyncListener() {
                                @Override
                                public void onGenerated(Palette palette) {
                                }
                            });
                        }

                        @Override
                        public void onError() {

                        }
                    });

                    txtName.setText(Menu_name);

                    String price = String.format(Locale.GERMAN, "%1$,.0f", Menu_price);
                   // txtPrice.setText(price + " " + ActivityMenuList.Currency);
                    DecimalFormat precision = new DecimalFormat("0.00");
                    txtPrice.setText(precision.format(Menu_price) + " ฿");
                    txtPeople.setText(serve_for + " " + getResources().getString(R.string.people));

                    if (menu_status != null && menu_status.equals("Available")) {
                        txtStatus.setText(getResources().getString(R.string.available));
                        txtStatus.setBackgroundResource(R.color.available);
                    } else {
                        txtStatus.setText(getResources().getString(R.string.sold));
                        txtStatus.setBackgroundResource(R.color.sold);
                    }

                    txtDescription.setBackgroundColor(Color.parseColor("#ffffff"));
                    txtDescription.setFocusableInTouchMode(false);
                    txtDescription.setFocusable(false);
                    txtDescription.getSettings().setDefaultTextEncodingName("UTF-8");

                    WebSettings webSettings = txtDescription.getSettings();
                    Resources res = getResources();
                    int fontSize = res.getInteger(R.integer.font_size);
                    webSettings.setDefaultFontSize(fontSize);
                    webSettings.setJavaScriptEnabled(true);

                    String mimeType = "text/html; charset=UTF-8";
                    String encoding = "utf-8";
                    String htmlText = Menu_description;

                    String text = "<html><head>"
                            + "<style type=\"text/css\">body{color: #525252;}"
                            + "</style></head>"
                            + "<body>"
                            + htmlText
                            + "</body></html>";

                    txtDescription.loadData(text, mimeType, encoding);

                } else {
                    txtAlert.setVisibility(View.VISIBLE);
                }

            }

        }
    }

    // method to parse json data from server
    public void parseJSONData() {

        try {
            // request data from menu detail API
            HttpClient client = new DefaultHttpClient();
            HttpConnectionParams.setConnectionTimeout(client.getParams(), 15000);
            HttpConnectionParams.setSoTimeout(client.getParams(), 15000);
            HttpUriRequest request = new HttpGet(MenuDetailAPI);
            HttpResponse response = client.execute(request);
            InputStream atomInputStream = response.getEntity().getContent();


            BufferedReader in = new BufferedReader(new InputStreamReader(atomInputStream));

            String line;
            String str = "";
            while ((line = in.readLine()) != null) {
                str += line;
            }

            // parse json data and store into tax and currency variables
            JSONObject json = new JSONObject(str);
            JSONArray data = json.getJSONArray("data"); // this is the "items: [ ] part

            for (int i = 0; i < data.length(); i++) {
                JSONObject object = data.getJSONObject(i);

                JSONObject menu = object.getJSONObject("menu_detail");

                Menu_image = menu.getString("menu_image");
                Menu_name = menu.getString("menu_name");

                NumberFormat numberFormat = NumberFormat.getNumberInstance(Locale.ENGLISH);
                DecimalFormat formatData = (DecimalFormat)numberFormat;
                formatData.applyPattern("#.##");
                Menu_price = Double.valueOf(formatData.format(menu.getDouble("price")));

                menu_status = menu.getString("menu_status");
                Menu_description = menu.getString("menu_description");
                serve_for = menu.getInt("serve_for");

            }


        } catch (MalformedURLException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        } catch (IOException e) {
            // TODO Auto-generated catch block
            IOConnect = 1;
            e.printStackTrace();
        } catch (JSONException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }


    // close database before back to previous page
    @Override
    public void onBackPressed() {
        // TODO Auto-generated method stub
        super.onBackPressed();
        dbhelper.close();
        finish();
    }


    @Override
    protected void onDestroy() {
        // TODO Auto-generated method stub
        //imageLoader.clearCache();
        super.onDestroy();
    }


    @Override
    public void onConfigurationChanged(final Configuration newConfig) {
        // Ignore orientation change to keep activity from restarting
        super.onConfigurationChanged(newConfig);
    }

//    public class SaveTask extends AsyncTask<String, String, String> {
//
//        private Context context;
//        private ProgressDialog pDialog;
//        URL myFileUrl;
//        Bitmap bmImg = null;
//        File file;
//
//        public SaveTask(Context context) {
//            this.context = context;
//        }
//
//        @Override
//        protected void onPreExecute() {
//
//            super.onPreExecute();
//
//            pDialog = new ProgressDialog(context);
//            pDialog.setMessage("Downloading Image ...");
//            pDialog.setIndeterminate(false);
//            pDialog.setCancelable(false);
//            pDialog.show();
//
//        }
//
//        @Override
//        protected String doInBackground(String... args) {
//            String as[] = null;
//            try {
//                myFileUrl = new URL(args[0]);
//                HttpURLConnection conn = (HttpURLConnection) myFileUrl.openConnection();
//                conn.setDoInput(true);
//                conn.connect();
//                InputStream is = conn.getInputStream();
//                bmImg = BitmapFactory.decodeStream(is);
//            } catch (IOException e) {
//                e.printStackTrace();
//            }
//            try {
//
//                String path = myFileUrl.getPath();
//                String idStr = path.substring(path.lastIndexOf('/') + 1);
//                File filepath = Environment.getExternalStorageDirectory();
//                File dir = new File(filepath.getAbsolutePath() + "/" + getResources().getString(R.string.app_name) + "/");
//                dir.mkdirs();
//                String fileName = "Image_" + "_" + idStr;
//                file = new File(dir, fileName);
//                FileOutputStream fos = new FileOutputStream(file);
//                bmImg.compress(Bitmap.CompressFormat.JPEG, 100, fos);
//                fos.flush();
//                fos.close();
//                as = new String[1];
//                as[0] = file.toString();
//
//                MediaScannerConnection.scanFile(ActivityMenuDetail.this, as, null, new android.media.MediaScannerConnection.OnScanCompletedListener() {
//                    public void onScanCompleted(String s1, Uri uri) {
//                    }
//                });
//
//            } catch (Exception e) {
//                e.printStackTrace();
//            }
//            return null;
//        }
//
//        @Override
//        protected void onPostExecute(String args) {
//            Toast.makeText(getApplicationContext(), "Image Saved Succesfully", Toast.LENGTH_SHORT).show();
//            pDialog.dismiss();
//        }
//    }

}
