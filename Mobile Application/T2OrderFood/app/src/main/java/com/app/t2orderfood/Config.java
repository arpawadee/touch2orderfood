package com.app.t2orderfood;

public class Config {

    //your admin panel url
    public static String ADMIN_PANEL_URL = "http://192.168.1.5/t2orderfood";

    //your applicationId or package name
    public static String PACKAGE_NAME = "com.app.t2orderfood";

    //set true if you want to enable RTL (Right To Left) mode, e.g : Arabic Language
    public static final boolean ENABLE_RTL_MODE = false;

    //*============================================================================*//
    //don't make changes anything
    //database path configuration
    public static String DB_PATH_START = "/data/data/";
    public static String DB_PATH_END = "/databases/";
    public static String DB_PATH = DB_PATH_START + PACKAGE_NAME + DB_PATH_END;

}