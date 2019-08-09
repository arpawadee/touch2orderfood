package com.app.t2orderfood.location;

import android.animation.Animator;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Build;
import android.support.v4.view.ViewCompat;
import android.view.View;
import android.view.ViewAnimationUtils;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.text.DecimalFormat;
import java.text.NumberFormat;

public class Helper {
	
	private static boolean DISPLAY_DEBUG = true;
	
	public static void noConnection(final Context context, String message) {
    	
        AlertDialog.Builder alert = new AlertDialog.Builder(context);
    	   
    	if (isOnline(context, false)){
    		String messageText = "";
        	if (message != null && DISPLAY_DEBUG){
        		messageText = "\n\n" + message;
        	}
        	
    		alert.setMessage("dialog connection description" + messageText);
    	   	alert.setPositiveButton("ok", null);
    	   	alert.setTitle("dialog connection title");
    	} else {
    		alert.setMessage("dialog internet desc");
     	   	alert.setPositiveButton("ok", null);
     	   	alert.setTitle("dialog internet title");
    	}
    	
    	alert.show();
     }	

    public static void noConnection(final Context context) {
        noConnection(context, null);
     }
    
    public static boolean isOnline(Context c, boolean showDialog) {
    	ConnectivityManager cm = (ConnectivityManager) 
    	c.getSystemService(Context.CONNECTIVITY_SERVICE);
    	NetworkInfo ni = cm.getActiveNetworkInfo();
    	 
    	if (ni != null && ni.isConnected())
    	  return true;
    	else
    	  if (showDialog){
    		  noConnection(c);
    	  }
    	  return false;
    }


    @SuppressLint("NewApi")
	public static void revealView(View toBeRevealed, View frame) {
		if (ViewCompat.isAttachedToWindow(toBeRevealed)) {
			if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
				int cx = (frame.getLeft() + frame.getRight()) / 2;
				int cy = (frame.getTop() + frame.getBottom()) / 2;
				int finalRadius = Math.max(frame.getWidth(), frame.getHeight());
				Animator anim = ViewAnimationUtils.createCircularReveal(toBeRevealed, cx, cy, 0, finalRadius);
				toBeRevealed.setVisibility(View.VISIBLE);
				anim.start();
			} else {
				toBeRevealed.setVisibility(View.VISIBLE);
			}
		}
	}

	@SuppressLint("NewApi")
	public static void setStatusBarColor(Activity mActivity, int color){
		try {
			if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.LOLLIPOP) {
				mActivity.getWindow().setStatusBarColor(color);
			}
		} catch (Exception e){
			Log.printStackTrace(e);
		}
	}

	public static String formatValue(double value) {
		if (value > 0){
			int power;
		    String suffix = " kmbt";
		    String formattedNumber = "";

		    NumberFormat formatter = new DecimalFormat("#,###.#");
		    power = (int)StrictMath.log10(value);
		    value = value/(Math.pow(10,(power/3)*3));
		    formattedNumber=formatter.format(value);
		    formattedNumber = formattedNumber + suffix.charAt(power/3);
		    return formattedNumber.length()>4 ?  formattedNumber.replaceAll("\\.[0-9]+", "") : formattedNumber;
		} else {
			return "0";
		}
	}

    public static String getDataFromUrl(String url){
        Log.v("INFO", "Requesting: " + url);

        StringBuffer chaine = new StringBuffer("");
        try {
            URL urlCon = new URL(url);

            HttpURLConnection connection = (HttpURLConnection) urlCon
                    .openConnection();
            connection.setRequestProperty("User-Agent", "Android");
            connection.setRequestMethod("GET");
            connection.setDoInput(true);
            connection.connect();

            InputStream inputStream = connection.getInputStream();

            BufferedReader rd = new BufferedReader(new InputStreamReader(
                    inputStream));
            String line = "";
            while ((line = rd.readLine()) != null) {
                chaine.append(line);
            }

        } catch (IOException e) {
            Log.printStackTrace(e);
        }

        return chaine.toString();
    }

	public static JSONObject getJSONObjectFromUrl(String url) {
		String data = getDataFromUrl(url);

		try {
			return new JSONObject(data);
		} catch (Exception e) {
            Log.e("INFO", "Error parsing JSON. Printing stacktrace now");
			Log.printStackTrace(e);
		}

		return null;
	}

    public static JSONArray getJSONArrayFromUrl(String url) {
        String data = getDataFromUrl(url);

        try {
            return new JSONArray(data);
        } catch (Exception e) {
            Log.e("INFO", "Error parsing JSON. Printing stacktrace now");
            Log.printStackTrace(e);
        }

        return null;
    }

}
