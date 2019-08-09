package com.app.t2orderfood.fragments;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.content.res.Resources;
import android.graphics.PorterDuff;
import android.graphics.Rect;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.app.Fragment;
import android.support.v4.content.ContextCompat;
import android.support.v4.view.PagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.util.TypedValue;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.app.t2orderfood.R;
import com.app.t2orderfood.activities.ActivityCategory;
import com.app.t2orderfood.activities.MainActivity;
import com.app.t2orderfood.adapters.AdapterHome;
import com.app.t2orderfood.models.ItemHome;
import com.app.t2orderfood.models.ItemImage;
import com.app.t2orderfood.utilities.Utils;
import com.balysv.materialripple.MaterialRippleLayout;

import java.util.ArrayList;
import java.util.List;

public class FragmentHome extends Fragment {

    private MainActivity mainActivity;
    private Toolbar toolbar;
    private ViewPager viewPager;
    private LinearLayout layout_dots;
  //  private AdapterImageSlider adapterImageSlider;
    private Runnable runnable = null;
    private Handler handler = new Handler();
    private RecyclerView recyclerView;
    private AdapterHome adapter;
    private List<ItemHome> itemHomeList;

    Button obtn;

    public FragmentHome() {
        // Required empty public constructor
    }

//    private static int[] array_image_place = {
//            R.drawable.image_slider1,
//            R.drawable.image_slider2,
//            R.drawable.image_slider3,
//            R.drawable.image_slider4
//    };
//
//    private static int[] array_title_place = {
//            R.string.slider_text1,
//            R.string.slider_text2,
//            R.string.slider_text3,
//            R.string.slider_text4,
//    };

    @Override
    public void onAttach(Context activity) {
        super.onAttach(activity);
        mainActivity = (MainActivity) activity;
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        final View view = inflater.inflate(R.layout.fragment_home, container, false);
        toolbar = (Toolbar) view.findViewById(R.id.toolbar);
        setupToolbar();

        obtn = (Button) view.findViewById(R.id.obtn);

        obtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(getActivity(), ActivityCategory.class);
                startActivity(intent);
            }
        });
//        layout_dots = (LinearLayout) view.findViewById(R.id.layout_dots);
//        viewPager = (ViewPager) view.findViewById(R.id.pager);
//        adapterImageSlider = new AdapterImageSlider(getActivity(), new ArrayList<ItemImage>());

//        final List<ItemImage> items = new ArrayList<>();
//        for (int i = 0; i < array_image_place.length; i++) {
//            ItemImage obj = new ItemImage();
//            obj.image = array_image_place[i];
//            obj.imageDrw = getResources().getDrawable(obj.image);
//            obj.name = array_title_place[i];
//            items.add(obj);
//        }
//
//        adapterImageSlider.setItems(items);
//        viewPager.setAdapter(adapterImageSlider);

        // displaying selected image first
//        viewPager.setCurrentItem(0);
//        addBottomDots(layout_dots, adapterImageSlider.getCount(), 0);
//        ((TextView) view.findViewById(R.id.title)).setText(items.get(0).name);
//        viewPager.addOnPageChangeListener(new ViewPager.OnPageChangeListener() {
//            @Override
//            public void onPageScrolled(int pos, float positionOffset, int positionOffsetPixels) {
//            }
//
//            @Override
//            public void onPageSelected(int pos) {
//                ((TextView) view.findViewById(R.id.title)).setText(items.get(pos).name);
//                addBottomDots(layout_dots, adapterImageSlider.getCount(), pos);
//            }
//
//            @Override
//            public void onPageScrollStateChanged(int state) {
//            }
//        });

//        startAutoSlider(adapterImageSlider.getCount());

//        recyclerView = (RecyclerView) view.findViewById(R.id.recycler_view);
//        recyclerView.setFocusable(false);
//        itemHomeList = new ArrayList<>();
//        adapter = new AdapterHome(getActivity(), itemHomeList);
//
//        RecyclerView.LayoutManager mLayoutManager = new GridLayoutManager(getActivity(), 3);
//        recyclerView.setLayoutManager(mLayoutManager);
//      //  recyclerView.addItemDecoration(new GridSpacingItemDecoration(3, dpToPx(5), true));
//       // recyclerView.setItemAnimator(new DefaultItemAnimator());
//
//        recyclerView.setAdapter(adapter);

     //   prepareMenu();

        return view;
    }

    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        mainActivity.setupNavigationDrawer(toolbar);
    }

    private void setupToolbar() {
        toolbar.setTitle(R.string.app_name);
        mainActivity.setSupportActionBar(toolbar);
    }

//    private void addBottomDots(LinearLayout layout_dots, int size, int current) {
//        ImageView[] dots = new ImageView[size];
//
//        layout_dots.removeAllViews();
//        for (int i = 0; i < dots.length; i++) {
//            dots[i] = new ImageView(getActivity());
//            int width_height = 15;
//            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(new ViewGroup.LayoutParams(width_height, width_height));
//            params.setMargins(10, 0, 10, 0);
//            dots[i].setLayoutParams(params);
//            dots[i].setImageResource(R.drawable.shape_circle_outline);
//            dots[i].setColorFilter(ContextCompat.getColor(getActivity(), R.color.grey), PorterDuff.Mode.SRC_ATOP);
//            layout_dots.addView(dots[i]);
//        }
//
//        if (dots.length > 0) {
//            dots[current].setImageResource(R.drawable.shape_circle);
//            dots[current].setColorFilter(ContextCompat.getColor(getActivity(), R.color.grey), PorterDuff.Mode.SRC_ATOP);
//        }
//    }

//    private void startAutoSlider(final int count) {
//        runnable = new Runnable() {
//            @Override
//            public void run() {
//                int pos = viewPager.getCurrentItem();
//                pos = pos + 1;
//                if (pos >= count) pos = 0;
//                viewPager.setCurrentItem(pos);
//                handler.postDelayed(runnable, 3000);
//            }
//        };
//        handler.postDelayed(runnable, 3000);
//    }
//
//    private static class AdapterImageSlider extends PagerAdapter {
//
//        private Activity act;
//        private List<ItemImage> items;
//
//        private AdapterImageSlider.OnItemClickListener onItemClickListener;
//
//        private interface OnItemClickListener {
//            void onItemClick(View view, ItemImage obj);
//        }
//
//        public void setOnItemClickListener(AdapterImageSlider.OnItemClickListener onItemClickListener) {
//            this.onItemClickListener = onItemClickListener;
//        }
//
//        // constructor
//        private AdapterImageSlider(Activity activity, List<ItemImage> items) {
//            this.act = activity;
//            this.items = items;
//        }
//
//        @Override
//        public int getCount() {
//            return this.items.size();
//        }
//
//        public ItemImage getItem(int pos) {
//            return items.get(pos);
//        }
//
//        public void setItems(List<ItemImage> items) {
//            this.items = items;
//            notifyDataSetChanged();
//        }
//
//        @Override
//        public boolean isViewFromObject(View view, Object object) {
//            return view == ((RelativeLayout) object);
//        }
//
//        @Override
//        public Object instantiateItem(ViewGroup container, int position) {
//            final ItemImage o = items.get(position);
//            LayoutInflater inflater = (LayoutInflater) act.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
//            View v = inflater.inflate(R.layout.item_slider_image, container, false);
//
//            ImageView image = (ImageView) v.findViewById(R.id.image);
//            MaterialRippleLayout lyt_parent = (MaterialRippleLayout) v.findViewById(R.id.lyt_parent);
//            Utils.displayImageOriginal(act, image, o.image);
//            lyt_parent.setOnClickListener(new View.OnClickListener() {
//                @Override
//                public void onClick(final View v) {
//                    if (onItemClickListener != null) {
//                        onItemClickListener.onItemClick(v, o);
//                    }
//                }
//            });
//
//            ((ViewPager) container).addView(v);
//
//            return v;
//        }
//
//        @Override
//        public void destroyItem(ViewGroup container, int position, Object object) {
//            ((ViewPager) container).removeView((RelativeLayout) object);
//
//        }
//
//    }

    @Override
    public void onDestroy() {
        if (runnable != null) handler.removeCallbacks(runnable);
        super.onDestroy();
    }

//    private void prepareMenu() {
//        int[] position = new int[]{
//                R.drawable.menu_image1,
//                R.drawable.menu_image2,
//                R.drawable.menu_image3,
//                R.drawable.menu_image4,
//                R.drawable.menu_image5,
//                R.drawable.menu_image6,
//                R.drawable.menu_image7,
//                R.drawable.menu_image8,
//                R.drawable.menu_image9
//        };
//
//        ItemHome a = new ItemHome(getResources().getString(R.string.menu_product), position[0]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_cart), position[1]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_reservation), position[2]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_gallery), position[3]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_news), position[4]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_location), position[5]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_social), position[6]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_info), position[7]);
//        itemHomeList.add(a);
//
//        a = new ItemHome(getResources().getString(R.string.menu_about), position[8]);
//        itemHomeList.add(a);
//
//        adapter.notifyDataSetChanged();
//    }
//
//    public class GridSpacingItemDecoration extends RecyclerView.ItemDecoration {
//
//        private int spanCount;
//        private int spacing;
//        private boolean includeEdge;
//
//        public GridSpacingItemDecoration(int spanCount, int spacing, boolean includeEdge) {
//            this.spanCount = spanCount;
//            this.spacing = spacing;
//            this.includeEdge = includeEdge;
//        }
//
//        @Override
//        public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
//            int position = parent.getChildAdapterPosition(view);
//            int column = position % spanCount;
//
//            if (includeEdge) {
//                outRect.left = spacing - column * spacing / spanCount;
//                outRect.right = (column + 1) * spacing / spanCount;
//
//                if (position < spanCount) {
//                    outRect.top = spacing;
//                }
//                outRect.bottom = spacing;
//            } else {
//                outRect.left = column * spacing / spanCount;
//                outRect.right = spacing - (column + 1) * spacing / spanCount;
//                if (position >= spanCount) {
//                    outRect.top = spacing;
//                }
//            }
//        }
//    }
//
//    private int dpToPx(int dp) {
//        Resources r = getResources();
//        return Math.round(TypedValue.applyDimension(TypedValue.COMPLEX_UNIT_DIP, dp, r.getDisplayMetrics()));
//    }

}
