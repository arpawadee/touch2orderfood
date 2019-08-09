package com.app.t2orderfood.activities;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.database.SQLException;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.support.design.widget.NavigationView;
import android.support.v4.app.ActivityCompat;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.fragments.FragmentHome;
import com.app.t2orderfood.notification.NotificationHandler;
import com.app.t2orderfood.utilities.DBHelper;
import com.app.t2orderfood.utilities.Utils;
import com.google.firebase.analytics.FirebaseAnalytics;

import java.io.IOException;


public class MainActivity extends AppCompatActivity implements NavigationView.OnNavigationItemSelectedListener {

    private final static String COLLAPSING_TOOLBAR_FRAGMENT_TAG = "collapsing_toolbar";
    private final static String SELECTED_TAG = "selected_index";
    private final static int COLLAPSING_TOOLBAR = 0;
    static DBHelper dbhelper;
    private static int selectedIndex;
    static final String TAG = "MainActivity";
    private NavigationView navigationView;
    private DrawerLayout drawerLayout;
    private ActionBarDrawerToggle actionBarDrawerToggle;


    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);



        if (Config.ENABLE_RTL_MODE) {
            if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.JELLY_BEAN_MR1) {
                getWindow().getDecorView().setLayoutDirection(View.LAYOUT_DIRECTION_RTL);
            }
        } else {
            Log.d("Log", "Working in Normal Mode, RTL Mode is Disabled");
        }

        firebaseAnalytics();

        navigationView = (NavigationView) findViewById(R.id.navigation_view);
        navigationView.setNavigationItemSelectedListener(this);
        drawerLayout = (DrawerLayout) findViewById(R.id.drawer_layout);

        if (savedInstanceState != null) {
            navigationView.getMenu().getItem(savedInstanceState.getInt(SELECTED_TAG)).setChecked(true);
            return;
        }

        selectedIndex = COLLAPSING_TOOLBAR;

        getSupportFragmentManager().beginTransaction().add(R.id.fragment_container,
                new FragmentHome(), COLLAPSING_TOOLBAR_FRAGMENT_TAG).commit();

        // checking internet connection
        if (!Utils.isNetworkAvailable(MainActivity.this)) {
            Toast.makeText(MainActivity.this, getString(R.string.no_internet), Toast.LENGTH_SHORT).show();
        }

        dbhelper = new DBHelper(this);

        // create database
        try {
            dbhelper.createDataBase();
        } catch (IOException ioe) {
            throw new Error("Unable to create database");
        }

        // then, the database will be open to use
        try {
            dbhelper.openDataBase();
        } catch (SQLException sqle) {
            throw sqle;
        }

        // if user has already ordered food previously then show activity_confirm dialog
        if (dbhelper.isPreviousDataExist()) {
            showAlertDialog();
        }

        isStoragePermissionGranted();

    }

    // show activity_confirm dialog to ask user to delete previous order or not
    public void showAlertDialog() {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setTitle(R.string.confirm);
        builder.setMessage(getString(R.string.db_exist_alert));
        builder.setCancelable(false);
        builder.setPositiveButton(getString(R.string.option_yes), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int which) {
                dbhelper.deleteAllData();
                dbhelper.close();
            }
        });

        builder.setNegativeButton(getString(R.string.option_no), new DialogInterface.OnClickListener() {

            public void onClick(DialogInterface dialog, int which) {
                dbhelper.close();
                dialog.cancel();
            }
        });
        AlertDialog alert = builder.create();
        alert.show();

    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        outState.putInt(SELECTED_TAG, selectedIndex);
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        return super.onOptionsItemSelected(item);
    }

    @Override
    public boolean onNavigationItemSelected(MenuItem menuItem) {

        switch (menuItem.getItemId()) {
            case R.id.drawer_home:
                if (!menuItem.isChecked()) {
                    selectedIndex = COLLAPSING_TOOLBAR;
                    menuItem.setChecked(true);
                    getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container,
                            new FragmentHome(), COLLAPSING_TOOLBAR_FRAGMENT_TAG).commit();
                }
                drawerLayout.closeDrawer(GravityCompat.START);
                return true;

            case R.id.drawer_menu:
                Intent menu = new Intent(getApplicationContext(), ActivityCategory.class);
                startActivity(menu);
                return true;

            case R.id.drawer_cart:
                Intent cart = new Intent(getApplicationContext(), ActivityCart.class);
                startActivity(cart);
                return true;

//            case R.id.drawer_reservation:
//                Intent reservation = new Intent(getApplicationContext(), ActivityReservation.class);
//                startActivity(reservation);
//                return true;
//
//            case R.id.drawer_gallery:
//                Intent gallery = new Intent(getApplicationContext(), ActivityGallery.class);
//                startActivity(gallery);
//                return true;
//
//            case R.id.drawer_news:
//                Intent news = new Intent(getApplicationContext(), ActivityNews.class);
//                startActivity(news);
//                return true;
//
//            case R.id.drawer_location:
//                boolean isAppInstalled = appInstalledOrNot("com.google.android.apps.maps");
//                if (isAppInstalled) {
//                    Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(getResources().getString(R.string.google_maps_url)));
//                    intent.setPackage("com.google.android.apps.maps");
//                    startActivity(intent);
//                } else {
//                    Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(getResources().getString(R.string.google_maps_url)));
//                    startActivity(intent);
//                }
//                return true;
//
//            case R.id.drawer_social:
//                Intent social = new Intent(getApplicationContext(), ActivityTabSocial.class);
//                startActivity(social);
//                return true;

//            case R.id.drawer_info:
//                Intent info = new Intent(getApplicationContext(), ActivityTabInformation.class);
//                startActivity(info);
//                return true;

//            case R.id.drawer_about:
//                Intent about = new Intent(getApplicationContext(), ActivityAbout.class);
//                startActivity(about);
//                return true;
//
//            case R.id.drawer_share:
//                Intent sendInt = new Intent(Intent.ACTION_SEND);
//                sendInt.putExtra(Intent.EXTRA_SUBJECT, getString(R.string.app_name));
//                sendInt.putExtra(Intent.EXTRA_TEXT, getString(R.string.app_name) + "\n" + getString(R.string.share_content) + "\n" + "https://play.google.com/store/apps/details?id=" + getPackageName());
//                sendInt.setType("text/plain");
//                startActivity(Intent.createChooser(sendInt, "Share"));
//                return true;
//
        }

        return false;
    }


    public void setupNavigationDrawer(Toolbar toolbar) {
        actionBarDrawerToggle = new ActionBarDrawerToggle(this, drawerLayout, toolbar,
                R.string.drawer_open, R.string.drawer_close) {
            @Override
            public void onDrawerOpened(View drawerView) {
                super.onDrawerOpened(drawerView);
            }

            @Override
            public void onDrawerClosed(View drawerView) {
                super.onDrawerClosed(drawerView);
            }
        };
        drawerLayout.addDrawerListener(actionBarDrawerToggle);
        actionBarDrawerToggle.syncState();
    }

    @Override
    public void onBackPressed() {
        DrawerLayout drawer = (DrawerLayout) findViewById(R.id.drawer_layout);
        if (drawer.isDrawerOpen(GravityCompat.START)) {
            drawer.closeDrawer(GravityCompat.START);
        } else {
            super.onBackPressed();
        }
    }

    public boolean isStoragePermissionGranted() {
        if (Build.VERSION.SDK_INT >= 23) {
            if (checkSelfPermission(android.Manifest.permission.WRITE_EXTERNAL_STORAGE)
                    == PackageManager.PERMISSION_GRANTED) {
                Log.v(TAG, "Permission is granted");
                return true;
            } else {

                Log.v(TAG, "Permission is revoked");
                ActivityCompat.requestPermissions(this, new String[]{android.Manifest.permission.WRITE_EXTERNAL_STORAGE}, 1);
                return false;
            }
        } else {
            Log.v(TAG, "Permission is granted");
            return true;
        }
    }

    private void firebaseAnalytics() {

        Bundle bundle = new Bundle();
        bundle.putString(FirebaseAnalytics.Param.ITEM_ID, "main_activity");
        bundle.putString(FirebaseAnalytics.Param.ITEM_NAME, "MainActivity");
        NotificationHandler.getFirebaseAnalytics().logEvent(FirebaseAnalytics.Event.SELECT_CONTENT, bundle);
        NotificationHandler.getFirebaseAnalytics().setAnalyticsCollectionEnabled(true);
        NotificationHandler.getFirebaseAnalytics().setMinimumSessionDuration(5000);
        NotificationHandler.getFirebaseAnalytics().setSessionTimeoutDuration(1000000);

    }

    private boolean appInstalledOrNot(String uri) {
        PackageManager pm = getPackageManager();
        try {
            pm.getPackageInfo(uri, PackageManager.GET_ACTIVITIES);
            return true;
        } catch (PackageManager.NameNotFoundException e) {
        }
        return false;
    }

}
