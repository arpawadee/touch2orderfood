package com.app.t2orderfood.adapters;

import android.content.Context;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.app.t2orderfood.R;
import com.app.t2orderfood.activities.ActivityAbout;
import com.app.t2orderfood.activities.ActivityCart;
import com.app.t2orderfood.activities.ActivityCategory;
import com.app.t2orderfood.activities.ActivityGallery;
import com.app.t2orderfood.activities.ActivityNews;
import com.app.t2orderfood.activities.ActivityReservation;
import com.app.t2orderfood.activities.ActivityTabInformation;
import com.app.t2orderfood.activities.ActivityTabSocial;
import com.app.t2orderfood.models.ItemHome;

import java.util.List;

public class AdapterHome extends RecyclerView.Adapter<AdapterHome.ViewHolder> {

    private Context context;
    private List<ItemHome> itemHomeList;

    public class ViewHolder extends RecyclerView.ViewHolder {
        public TextView title;
        public ImageView thumbnail;
        public LinearLayout linearLayout;

        public ViewHolder(View view) {
            super(view);
            title = (TextView) view.findViewById(R.id.title);
            thumbnail = (ImageView) view.findViewById(R.id.thumbnail);
            linearLayout = (LinearLayout) view.findViewById(R.id.linearLayout);
        }

    }

    public AdapterHome(Context context, List<ItemHome> itemHomeList) {
        this.context = context;
        this.itemHomeList = itemHomeList;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {

        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.lsv_item_home, parent, false);

        return new ViewHolder(itemView);

    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {
        ItemHome itemHome = itemHomeList.get(position);
        holder.title.setText(itemHome.getName());
        holder.thumbnail.setImageResource(itemHome.getThumbnail());

        holder.linearLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (position == 0) {
                    Intent intent = new Intent(context, ActivityCategory.class);
                    context.startActivity(intent);
                } else if (position == 1) {
                    Intent intent = new Intent(context, ActivityCart.class);
                    context.startActivity(intent);
                } else if (position == 2) {
                    Intent intent = new Intent(context, ActivityReservation.class);
                    context.startActivity(intent);
                } else if (position == 3) {
                    Intent intent = new Intent(context, ActivityGallery.class);
                    context.startActivity(intent);
                } else if (position == 4) {
                    Intent intent = new Intent(context, ActivityNews.class);
                    context.startActivity(intent);
                } else if (position == 5) {
                    boolean isAppInstalled = appInstalledOrNot("com.google.android.apps.maps");
                    if (isAppInstalled) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(context.getResources().getString(R.string.google_maps_url)));
                        intent.setPackage("com.google.android.apps.maps");
                        context.startActivity(intent);
                    } else {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(context.getResources().getString(R.string.google_maps_url)));
                        context.startActivity(intent);
                    }
                } else if (position == 6) {
                    Intent intent = new Intent(context, ActivityTabSocial.class);
                    context.startActivity(intent);
                } else if (position == 7) {
                    Intent intent = new Intent(context, ActivityTabInformation.class);
                    context.startActivity(intent);
                } else if (position == 8) {
                    Intent intent = new Intent(context, ActivityAbout.class);
                    context.startActivity(intent);
                }
            }
        });

    }

    private boolean appInstalledOrNot(String uri) {
        PackageManager pm = context.getPackageManager();
        try {
            pm.getPackageInfo(uri, PackageManager.GET_ACTIVITIES);
            return true;
        } catch (PackageManager.NameNotFoundException e) {
        }
        return false;
    }

    @Override
    public int getItemCount() {
        return itemHomeList.size();
    }
}
