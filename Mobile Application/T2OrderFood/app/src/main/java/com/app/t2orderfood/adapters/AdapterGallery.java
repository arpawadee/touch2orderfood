package com.app.t2orderfood.adapters;

import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.activities.ActivityGalleryDetail;
import com.app.t2orderfood.json.JsonConfig;
import com.app.t2orderfood.models.ItemGallery;
import com.squareup.picasso.Picasso;

import java.util.List;

public class AdapterGallery extends RecyclerView.Adapter<AdapterGallery.ViewHolder> {

    private Context context;
    private List<ItemGallery> arrayItemGalleryList;
    ItemGallery ItemGalleryList;
    String[] str_gallery, str_cid, str_cat_id, str_image, str_name, str_desc;

    public class ViewHolder extends RecyclerView.ViewHolder {

        public ImageView image;
        public RelativeLayout relativeLayout;

        public ViewHolder(View view) {
            super(view);

            image = (ImageView) view.findViewById(R.id.gallery_image);
            relativeLayout = (RelativeLayout) view.findViewById(R.id.relativeLayout);

        }

    }

    public AdapterGallery(Context context, List<ItemGallery> arrayItemGalleryList) {
        this.context = context;
        this.arrayItemGalleryList = arrayItemGalleryList;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {

        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.lsv_item_gallery, parent, false);

        return new ViewHolder(itemView);

    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {

        ItemGalleryList = arrayItemGalleryList.get(position);

        Picasso.with(context).load(Config.ADMIN_PANEL_URL + "/upload/gallery/thumbs/" +
                ItemGalleryList.getGalleryImage()).placeholder(R.drawable.ic_loading).into(holder.image);

        holder.relativeLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                ItemGalleryList = arrayItemGalleryList.get(position);
                int pos = Integer.parseInt(ItemGalleryList.getCatId());
                Intent intent = new Intent(context, ActivityGalleryDetail.class);
                intent.putExtra("POSITION", pos);
                JsonConfig.GALLERY_ITEMID = ItemGalleryList.getCatId();
                context.startActivity(intent);

            }
        });

    }

    @Override
    public int getItemCount() {
        return arrayItemGalleryList.size();
    }

}
