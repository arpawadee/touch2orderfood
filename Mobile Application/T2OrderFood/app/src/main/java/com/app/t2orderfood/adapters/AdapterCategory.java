package com.app.t2orderfood.adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.activities.ActivityCategory;
import com.app.t2orderfood.models.ItemCategory;
import com.squareup.picasso.Picasso;

import java.util.List;

public class AdapterCategory extends RecyclerView.Adapter<AdapterCategory.ViewHolder> {

    private Context context;
    private List<ItemCategory> arrayItemCategory;

    public class ViewHolder extends RecyclerView.ViewHolder {

        TextView txtText;
        ImageView imgThumb;

        public ViewHolder(View view) {
            super(view);

            txtText = (TextView) view.findViewById(R.id.txtName);
            imgThumb = (ImageView) view.findViewById(R.id.imgThumb);

        }

    }

    public AdapterCategory(Context context, List<ItemCategory> arrayItemCategory) {
        this.context = context;
        this.arrayItemCategory = arrayItemCategory;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.lsv_item_category, parent, false);
        return new ViewHolder(itemView);

    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {

        holder.txtText.setText(ActivityCategory.category_name.get(position));

        Picasso.with(context)
                .load(Config.ADMIN_PANEL_URL + "/" + ActivityCategory.category_image.get(position))
                .placeholder(R.drawable.ic_loading)
                .into(holder.imgThumb);

    }

    @Override
    public int getItemCount() {
        return ActivityCategory.category_id.size();
    }

}
