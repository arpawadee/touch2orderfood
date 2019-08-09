package com.app.t2orderfood.models;

public class ItemMenu {

    private String MenuId;
    private String MenuImage;
    private String MenuName;
    private String MenuPrice;

    public ItemMenu(String menuId, String menuImage, String menuName, String menuPrice) {
        MenuId = menuId;
        MenuImage = menuImage;
        MenuName = menuName;
        MenuPrice = menuPrice;
    }

    public String getMenuId() {
        return MenuId;
    }

    public void setMenuId(String menuId) {
        MenuId = menuId;
    }

    public String getMenuImage() {
        return MenuImage;
    }

    public void setMenuImage(String menuImage) {
        MenuImage = menuImage;
    }

    public String getMenuName() {
        return MenuName;
    }

    public void setMenuName(String menuName) {
        MenuName = menuName;
    }

    public String getMenuPrice() {
        return MenuPrice;
    }

    public void setMenuPrice(String menuPrice) {
        MenuPrice = menuPrice;
    }

}
