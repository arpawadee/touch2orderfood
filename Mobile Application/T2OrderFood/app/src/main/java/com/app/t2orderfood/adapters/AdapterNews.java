package com.app.t2orderfood.adapters;

import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.t2orderfood.Config;
import com.app.t2orderfood.R;
import com.app.t2orderfood.activities.ActivityNewsDetail;
import com.app.t2orderfood.json.JsonConfig;
import com.app.t2orderfood.models.ItemNews;
import com.squareup.picasso.Picasso;

import java.util.List;

public class AdapterNews extends RecyclerView.Adapter<AdapterNews.ViewHolder> {

    private Context context;
    private List<ItemNews> arrayItemNewsList;
    ItemNews itemNewsList;

    public class ViewHolder extends RecyclerView.ViewHolder {

        public ImageView image;
        public TextView title;
        public TextView date;
        public RelativeLayout relativeLayout;

        public ViewHolder(View view) {
            super(view);

            title = (TextView) view.findViewById(R.id.news_title);
            date = (TextView) view.findViewById(R.id.news_date);
            image = (ImageView) view.findViewById(R.id.news_image);
            relativeLayout = (RelativeLayout) view.findViewById(R.id.relativeLayout);

        }

    }

    public AdapterNews(Context context, List<ItemNews> arrayItemNewsList) {
        this.context = context;
        this.arrayItemNewsList = arrayItemNewsList;
    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {

        View itemView = LayoutInflater.from(parent.getContext()).inflate(R.layout.lsv_item_news_list, parent, false);

        return new ViewHolder(itemView);

    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {

        itemNewsList = arrayItemNewsList.get(position);

        holder.title.setText(itemNewsList.getNewsHeading());
        holder.date.setText(itemNewsList.getNewsDate());

        Picasso.with(context).load(Config.ADMIN_PANEL_URL + "/upload/news/thumbs/" +
                itemNewsList.getNewsImage()).placeholder(R.drawable.ic_loading).into(holder.image);

        holder.relativeLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                itemNewsList = arrayItemNewsList.get(position);

                int pos = Integer.parseInt(itemNewsList.getCatId());

                Intent intent = new Intent(context, ActivityNewsDetail.class);
                intent.putExtra("POSITION", pos);
                JsonConfig.NEWS_ITEMID = itemNewsList.getCatId();

                context.startActivity(intent);

            }
        });

    }

    @Override
    public int getItemCount() {
        return arrayItemNewsList.size();
    }

}
