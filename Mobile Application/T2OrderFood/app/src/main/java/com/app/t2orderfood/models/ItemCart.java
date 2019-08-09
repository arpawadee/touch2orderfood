package com.app.t2orderfood.models;

public class ItemCart {

    private String MenuId;
    private String MenuName;
    private String MenuQuantity;
    private String MenuPrice;

    public ItemCart(String menuId, String menuName, String menuQuantity, String menuPrice) {
        MenuId = menuId;
        MenuName = menuName;
        MenuQuantity = menuQuantity;
        MenuPrice = menuPrice;
    }

    public String getMenuId() {
        return MenuId;
    }

    public void setMenuId(String menuId) {
        MenuId = menuId;
    }

    public String getMenuName() {
        return MenuName;
    }

    public void setMenuName(String menuName) {
        MenuName = menuName;
    }

    public String getMenuQuantity() {
        return MenuQuantity;
    }

    public void setMenuQuantity(String menuQuantity) {
        MenuQuantity = menuQuantity;
    }

    public String getMenuPrice() {
        return MenuPrice;
    }

    public void setMenuPrice(String menuPrice) {
        MenuPrice = menuPrice;
    }
}
