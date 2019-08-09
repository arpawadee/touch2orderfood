package com.app.t2orderfood.json;

import java.io.Serializable;

public class JsonConfig implements Serializable {

    private static final long serialVersionUID = 1L;

    public static final String CATEGORY_ARRAY_NAME = "data";

    public static final String CATEGORY_ITEM_CAT_ID = "nid";
    public static final String CATEGORY_ITEM_NEWSHEADING = "news_heading";
    public static final String CATEGORY_ITEM_NEWSDESCRI = "news_description";
    public static final String CATEGORY_ITEM_NEWSIMAGE = "news_image";
    public static final String CATEGORY_ITEM_NEWSDATE = "news_date";
    public static String NEWS_ITEMID;

    public static final String GALLERY_ITEM_CAT_ID = "gid";
    public static final String GALLERY_ITEM_NEWSHEADING = "gallery_name";
    public static final String GALLERY_ITEM_NEWSDESCRI = "gallery_description";
    public static final String GALLERY_ITEM_NEWSIMAGE = "gallery_image";
    public static String GALLERY_ITEMID;


}
