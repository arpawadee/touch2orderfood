package com.app.t2orderfood.models;

public class ItemNews {
	
	private String CId;
	private String CatId;
	private String NewsHeading;
 	private String NewsDescription;
	private String NewsImage;
	private String NewsDate;
	
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
	
 	public String getNewsHeading() {
		return NewsHeading;
	}

	public void setNewsHeading(String newsheading) {
		this.NewsHeading = newsheading;
	}
	
	public String getNewsDescription() {
		return NewsDescription;
	}

	public void setNewsDescription(String newsdescription) {
		this.NewsDescription = newsdescription;
	}
	public String getNewsImage() {
		return NewsImage;
	}

	public void setNewsImage(String newsimage) {
		this.NewsImage = newsimage;
	}
	public String getNewsDate() {
		return NewsDate;
	}

	public void setNewsDate(String newsdate) {
		this.NewsDate = newsdate;
	}

}
