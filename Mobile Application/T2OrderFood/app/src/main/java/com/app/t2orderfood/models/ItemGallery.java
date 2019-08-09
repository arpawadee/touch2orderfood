package com.app.t2orderfood.models;

public class ItemGallery {
	
	private String CId;
	private String CatId;
	private String GalleryName;
 	private String GalleryDescription;
	private String GalleryImage;
	
	public String getCId() {
		return CId;
	}

	public void setCId(String cid) {
		this.CId = cid;
	}

	public String getCatId() {
		return CatId;
	}

	public void setCatId(String catid) {
		this.CatId = catid;
	}
	
 	public String getGalleryName() {
		return GalleryName;
	}

	public void setGalleryName(String newsheading) {
		this.GalleryName = newsheading;
	}
	
	public String getGalleryDescription() {
		return GalleryDescription;
	}

	public void setGalleryDescription(String newsdescription) {
		this.GalleryDescription = newsdescription;
	}

	public String getGalleryImage() {
		return GalleryImage;
	}

	public void setGalleryImage(String newsimage) {
		this.GalleryImage = newsimage;
	}

}
