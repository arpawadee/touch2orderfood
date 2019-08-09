package com.app.t2orderfood.activities;

import android.content.res.Resources;
import android.graphics.Rect;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.TypedValue;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ProgressBar;
import android.widget.Toast;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.adapters.AdapterGallery;
import com.app.t2orderfood.json.JsonConfig;
import com.app.t2orderfood.json.JsonUtils;
import com.app.t2orderfood.models.ItemGallery;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

public class ActivityGallery extends AppCompatActivity {

    RecyclerView recyclerView;
    List<ItemGallery> arrayItemGallery;
    AdapterGallery AdapterGalleryRecent;
    ArrayList<String> array_gallery, array_cid, array_cat_id, array_image, array_name, array_desc;
    String[] str_gallery, str_cid, str_cat_id, str_image, str_name, str_desc;
    ItemGallery ItemGallery;
    JsonUtils util;
    int textLength = 0;
    SwipeRefreshLayout swipeRefreshLayout = null;
    ProgressBar progressBar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_news);

        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);
        final android.support.v7.app.ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setDisplayHomeAsUpEnabled(true);
            getSupportActionBar().setTitle(R.string.title_gallery);
        }

        recyclerView = (RecyclerView) findViewById(R.id.recycler_view);
        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        swipeRefreshLayout = (SwipeRefreshLayout) findViewById(R.id.swipeRefreshLayout);
        swipeRefreshLayout.setColorSchemeResources(R.color.orange, R.color.green, R.color.blue, R.color.red);

        RecyclerView.LayoutManager mLayoutManager = new GridLayoutManager(getApplicationContext(), 2);
        recyclerView.setLayoutManager(mLayoutManager);
        recyclerView.addItemDecoration(new GridSpacingItemDecoration(2, dpToPx(3), true));
        recyclerView.setItemAnimator(new DefaultItemAnimator());

        arrayItemGallery = new ArrayList<ItemGallery>();
        array_gallery = new ArrayList<String>();
        array_cid = new ArrayList<String>();
        array_cat_id = new ArrayList<String>();
        array_name = new ArrayList<String>();
        array_image = new ArrayList<String>();
        array_desc = new ArrayList<String>();
//        array_date = new ArrayList<String>();

        str_gallery = new String[array_gallery.size()];
        str_cid = new String[array_cid.size()];
        str_cat_id = new String[array_cat_id.size()];
        str_name = new String[array_name.size()];
        str_image = new String[array_image.size()];
        str_desc = new String[array_desc.size()];
//        str_desc = new String[array_date.size()];

        util = new JsonUtils(ActivityGallery.this);

        if (JsonUtils.isNetworkAvailable(ActivityGallery.this)) {
            new MyTask().execute(Config.ADMIN_PANEL_URL + "/api/get-gallery.php?latest_gallery");
        } else {
            Toast.makeText(getApplicationContext(), getResources().getString(R.string.failed_connect_network), Toast.LENGTH_SHORT).show();
        }

        // Using to refresh webpage when user swipes the screen
        swipeRefreshLayout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                new Handler().postDelayed(new Runnable() {
                    @Override
                    public void run() {
                        swipeRefreshLayout.setRefreshing(false);
                        clearData();
                        new MyTask().execute(Config.ADMIN_PANEL_URL + "/api/get-gallery.php?latest_gallery");
                    }
                }, 3000);
            }
        });

    }

    public void clearData() {
        int size = this.arrayItemGallery.size();
        if (size > 0) {
            for (int i = 0; i < size; i++) {
                this.arrayItemGallery.remove(0);
            }

            AdapterGalleryRecent.notifyItemRangeRemoved(0, size);
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
//                        objItem.setNewsDate(objJson.getString(JsonConfig.CATEGORY_ITEM_NEWSDATE));

                        arrayItemGallery.add(objItem);

                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
                for (int j = 0; j < arrayItemGallery.size(); j++) {

                    ItemGallery = arrayItemGallery.get(j);

                    array_cat_id.add(ItemGallery.getCatId());
                    str_cat_id = array_cat_id.toArray(str_cat_id);

                    array_cid.add(String.valueOf(ItemGallery.getCId()));
                    str_cid = array_cid.toArray(str_cid);

                    array_image.add(String.valueOf(ItemGallery.getGalleryImage()));
                    str_image = array_image.toArray(str_image);

                    array_name.add(String.valueOf(ItemGallery.getGalleryName()));
                    str_name = array_name.toArray(str_name);

                    array_desc.add(String.valueOf(ItemGallery.getGalleryDescription()));
                    str_desc = array_desc.toArray(str_desc);
//
//                    array_date.add(String.valueOf(ItemGallery.getNewsDate()));
//                    str_desc = array_date.toArray(str_desc);

                }

                setAdapterToRecyclerView();
            }

        }
    }

    public void setAdapterToRecyclerView() {
        AdapterGalleryRecent = new AdapterGallery(ActivityGallery.this, arrayItemGallery);
        recyclerView.setAdapter(AdapterGalleryRecent);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        getMenuInflater().inflate(R.menu.main, menu);

//        final SearchView searchView = (SearchView) MenuItemCompat.getActionView(menu.findItem(R.id.search));
//
//        final MenuItem searchMenuItem = menu.findItem(R.id.search);
//        searchView.setQueryHint(getResources().getString(R.string.search_query_text));
//
//        searchView.setOnQueryTextFocusChangeListener(new View.OnFocusChangeListener() {
//
//                    @Override
//                    public void onFocusChange(View v, boolean hasFocus) {
//                        if (!hasFocus) {
//                            searchMenuItem.collapseActionView();
//                            searchView.setQuery("", false);
//                        }
//                    }
//                });
//
//        searchView.setOnQueryTextListener(new SearchView.OnQueryTextListener() {
//            @Override
//            public boolean onQueryTextChange(String newText) {
//
//                textLength = newText.length();
//                arrayItemGallery.clear();
//
//                for (int i = 0; i < str_name.length; i++) {
//                    if (textLength <= str_name[i].length()) {
//                        if (str_name[i].toLowerCase().contains(newText.toLowerCase())) {
//
//                            ItemGallery objItem = new ItemGallery();
//
//                            objItem.setCatId(str_cat_id[i]);
//                            objItem.setCId(str_cid[i]);
//                            objItem.setNewsDate(str_desc[i]);
//                            objItem.setNewsDescription(str_desc[i]);
//                            objItem.setNewsHeading(str_name[i]);
//                            objItem.setNewsImage(str_image[i]);
//                            arrayItemGallery.add(objItem);
//                        }
//                    }
//                }
//
//                setAdapterToRecyclerView();
//                return false;
//            }
//
//            @Override
//            public boolean onQueryTextSubmit(String query) {
//
//                return true;
//            }
//        });
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem menuItem) {
        switch (menuItem.getItemId()) {
            case android.R.id.home:
                this.finish();
                return true;

            default:
                return super.onOptionsItemSelected(menuItem);
        }
    }

    public class GridSpacingItemDecoration extends RecyclerView.ItemDecoration {

        private int spanCount;
        private int spacing;
        private boolean includeEdge;

        public GridSpacingItemDecoration(int spanCount, int spacing, boolean includeEdge) {
            this.spanCount = spanCount;
            this.spacing = spacing;
            this.includeEdge = includeEdge;
        }

        @Override
        public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
            int position = parent.getChildAdapterPosition(view);
            int column = position % spanCount;

            if (includeEdge) {
                outRect.left = spacing - column * spacing / spanCount;
                outRect.right = (column + 1) * spacing / spanCount;

                if (position < spanCount) {
                    outRect.top = spacing;
                }
                outRect.bottom = spacing;
            } else {
                outRect.left = column * spacing / spanCount;
                outRect.right = spacing - (column + 1) * spacing / spanCount;
                if (position >= spanCount) {
                    outRect.top = spacing;
                }
            }
        }
    }

    private int dpToPx(int dp) {
        Resources r = getResources();
        return Math.round(TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, dp, r.getDisplayMetrics()));
    }

}
