package com.app.t2orderfood.activities;

import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.drawable.BitmapDrawable;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.graphics.Palette;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.json.JsonConfig;
import com.app.t2orderfood.json.JsonUtils;
import com.app.t2orderfood.models.ItemGallery;
import com.app.t2orderfood.utilities.TouchImageView;
import com.squareup.picasso.Callback;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class ActivityGalleryDetail extends AppCompatActivity {

    String str_cid, str_cat_id, str_cat_image, str_cat_name, str_name, str_image, str_desc;
    TextView gallery_name, gallery_description;
    ImageView gallery_image;
    LinearLayout linearLayout;
    List<ItemGallery> arrayItemGallery;
    ItemGallery ItemGallery;
    ProgressBar progressBar;
    RelativeLayout relativeLayout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        setContentView(R.layout.activity_gallery_detail);

        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        final android.support.v7.app.ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle(R.string.title_gallery);
        }

        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        relativeLayout = (RelativeLayout) findViewById(R.id.relativeLayout);

        gallery_image = (TouchImageView) findViewById(R.id.gallery_image);
        gallery_name = (TextView) findViewById(R.id.gallery_name);
        gallery_description = (TextView) findViewById(R.id.gallery_description);

        arrayItemGallery = new ArrayList<ItemGallery>();

        if (JsonUtils.isNetworkAvailable(ActivityGalleryDetail.this)) {
            new MyTask().execute(Config.ADMIN_PANEL_URL + "/api/get-gallery.php?gid=" + JsonConfig.GALLERY_ITEMID);
        } else {
            Toast.makeText(getApplicationContext(), getResources().getString(R.string.failed_connect_network), Toast.LENGTH_SHORT).show();
        }

    }

    private class MyTask extends AsyncTask<String, Void, String> {

        @Override
        protected void onPreExecute() {
            super.onPreExecute();

            progressBar.setVisibility(View.VISIBLE);

        }

        @Override
        protected String doInBackground(String... params) {
            return JsonUtils.getJSONString(params[0]);
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);

            progressBar.setVisibility(View.GONE);
            relativeLayout.setVisibility(View.VISIBLE);

            if (null == result || result.length() == 0) {
                Toast.makeText(getApplicationContext(), getResources().getString(R.string.failed_connect_network), Toast.LENGTH_SHORT).show();

            } else {

                try {
                    JSONObject mainJson = new JSONObject(result);
                    JSONArray jsonArray = mainJson.getJSONArray(JsonConfig.CATEGORY_ARRAY_NAME);
                    JSONObject objJson = null;
                    for (int i = 0; i < jsonArray.length(); i++) {
                        objJson = jsonArray.getJSONObject(i);

                        ItemGallery objItem = new ItemGallery();

                        objItem.setCatId(objJson.getString(JsonConfig.GALLERY_ITEM_CAT_ID));
                        objItem.setGalleryImage(objJson.getString(JsonConfig.GALLERY_ITEM_NEWSIMAGE));
                        objItem.setGalleryName(objJson.getString(JsonConfig.GALLERY_ITEM_NEWSHEADING));
                        objItem.setGalleryDescription(objJson.getString(JsonConfig.GALLERY_ITEM_NEWSDESCRI));

                        arrayItemGallery.add(objItem);

                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

                setAdapterToRecyclerView();
            }

        }
    }

    public void setAdapterToRecyclerView() {

            ItemGallery = arrayItemGallery.get(0);
            str_cid = ItemGallery.getCId();
            str_cat_id = ItemGallery.getCatId();
            str_name = ItemGallery.getGalleryName();
            str_image = ItemGallery.getGalleryImage();
            str_desc = ItemGallery.getGalleryDescription();

            gallery_name.setText(str_name);
            gallery_description.setText(str_desc);

            Picasso.with(this).load(Config.ADMIN_PANEL_URL + "/upload/gallery/" + ItemGallery.getGalleryImage()).placeholder(R.drawable.ic_loading).into(gallery_image, new Callback() {
                @Override
                public void onSuccess() {
                    Bitmap bitmap = ((BitmapDrawable) gallery_image.getDrawable()).getBitmap();
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

    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.menu_news, menu);
        return super.onCreateOptionsMenu(menu);
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                onBackPressed();
                break;

            case R.id.menu_share:

                String formattedString = android.text.Html.fromHtml(str_desc).toString();
                Intent sendIntent = new Intent();
                sendIntent.setAction(Intent.ACTION_SEND);
                sendIntent.putExtra(Intent.EXTRA_TEXT, str_name + "\n" + formattedString + "\n" + getResources().getString(R.string.share_content) + "https://play.google.com/store/apps/details?id=" + getPackageName());
                sendIntent.setType("text/plain");
                startActivity(sendIntent);

                break;

            default:
                return super.onOptionsItemSelected(menuItem);
        }
        return true;
    }

    @Override
    public void onStart() {
        super.onStart();
    }

    @Override
    public void onStop() {
        super.onStop();
    }

    @Override
    protected void onPause() {
        super.onPause();
    }

    @Override
    protected void onResume() {
        super.onResume();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
    }

}
