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
import com.app.t2orderfood.activities.ActivityMenuList;
import com.app.t2orderfood.models.ItemMenu;
import com.squareup.picasso.Picasso;

import java.text.NumberFormat;
import java.util.Currency;
import java.util.List;
import java.util.Locale;

public class AdapterMenu extends RecyclerView.Adapter<AdapterMenu.ViewHolder> {

    private Context context;
    private List<ItemMenu> arrayItemMenu;

    public class ViewHolder extends RecyclerView.ViewHolder {

        TextView txtText, txtSubText;
        ImageView imgThumb;

        public ViewHolder(View view) {
            super(view);

            txtText = (TextView) view.findViewById(R.id.txtName);
            txtSubText = (TextView) view.findViewById(R.id.txtPrice);
            imgThumb = (ImageView) view.findViewById(R.id.imgThumb);

        }

    }

    public AdapterMenu(Context context, List<ItemMenu> arrayItemMenu) {
        this.context = context;
        this.arrayItemMenu = arrayItemMenu;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {

        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.lsv_item_menu, parent, false);

        return new ViewHolder(itemView);

    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {

        holder.txtText.setText(ActivityMenuList.Menu_name.get(position));

        String price = String.format(Locale.GERMAN, "%1$,.0f", ActivityMenuList.Menu_price.get(position));

     //   holder.txtSubText.setText(price + " " + ActivityMenuList.Currency);

        holder.txtSubText.setText(price + " ฿");




        Picasso.with(context)
                .load(Config.ADMIN_PANEL_URL + "/" + ActivityMenuList.Menu_image.get(position))
                .placeholder(R.drawable.ic_loading)
                .resize(250, 250)
                .centerCrop()
                .into(holder.imgThumb);

    }

    @Override
    public int getItemCount() {
        return ActivityMenuList.Menu_ID.size();
    }

}
