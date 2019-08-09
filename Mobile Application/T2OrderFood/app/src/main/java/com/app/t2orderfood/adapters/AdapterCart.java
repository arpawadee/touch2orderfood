package com.app.t2orderfood.adapters;

import android.content.Context;
import android.graphics.drawable.Drawable;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.activities.ActivityCart;
import com.app.t2orderfood.activities.ActivityMenuList;
import com.app.t2orderfood.models.ItemCart;

import java.text.DecimalFormat;
import java.util.List;
import java.util.Locale;

public class AdapterCart extends RecyclerView.Adapter<AdapterCart.ViewHolder> {

    private Context context;
    private List<ItemCart> arrayItemCart;

    public class ViewHolder extends RecyclerView.ViewHolder {

        TextView txtMenuName;
        TextView txtQuantity;
        TextView txtPrice;
        TextView txtQ;
        TextView txtmul;
        TextView txtPricePerItem;
        ImageView bin_icon;

        public ViewHolder(View view) {
            super(view);

            txtMenuName = (TextView) view.findViewById(R.id.txtMenuName);
            txtQuantity = (TextView) view.findViewById(R.id.txtQuantity);
            txtPrice = (TextView) view.findViewById(R.id.txtPrice);
            txtQ = (TextView) view.findViewById(R.id.txtQ);
       //     txtmul = (TextView) view.findViewById(R.id.txtmul);
     //       txtPricePerItem = (TextView) view.findViewById(R.id.txtPricePerItem);
       //     bin_icon = (ImageView) view.findViewById(R.id.bin_icon);

        }

    }

    public AdapterCart(Context context, List<ItemCart> arrayItemCart) {
        this.context = context;
        this.arrayItemCart = arrayItemCart;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        if (Config.ENABLE_RTL_MODE) {
            View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.lsv_item_order_list_rtl, parent, false);
            return new ViewHolder(itemView);
        } else {
            View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.lsv_item_order_list, parent, false);
            return new ViewHolder(itemView);
        }
    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {

        holder.txtMenuName.setText(ActivityCart.Menu_name.get(position));
        holder.txtQuantity.setText(String.valueOf(ActivityCart.Quantity.get(position)));
        holder.txtQ.setText("Qty");
      //  holder.txtmul.setText("x");

       // String price = String.format(Locale.GERMAN, "%1$,.0f", ActivityCart.Sub_total_price.get(position));
        DecimalFormat formatter = new DecimalFormat("#,###,###");
        String price2 =  formatter.format(ActivityCart.Sub_total_price.get(position));
       // holder.txtPrice.setText(price + " " + ActivityMenuList.Currency);
        holder.txtPrice.setText(price2 + " ฿");
      //  holder.txtPricePerItem.setText(p);


    }

    @Override
    public int getItemCount() {
        return ActivityCart.Menu_ID.size();
    }

}
