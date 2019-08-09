package com.app.t2orderfood.models;

public class ItemCategory {

    private String CategoryId;
    private String CategoryName;
    private String CategoryImage;


    public ItemCategory(String categoryid, String categoryname, String categoryimage) {

        this.CategoryId = categoryid;
        this.CategoryName = categoryname;
        this.CategoryImage = categoryimage;
    }

    public ItemCategory() {
    }

    public String getCategoryName() {
        return CategoryName;
    }

    public void setCategoryImage(String categoryimage) {
        this.CategoryImage = categoryimage;
    }

    public String getCategoryImage() {
        return CategoryImage;
    }

    public void setCategoryName(String categoryname) {
        this.CategoryName = categoryname;
    }

    public String getCategoryId() {
        return CategoryId;
    }

    public void setCategoryId(String categoryid) {
        this.CategoryId = categoryid;
    }

}
