package com.example.myapplication;


import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;
import java.util.List;

public class PharmacyAdapter extends RecyclerView.Adapter<PharmacyAdapter.PharmacyViewHolder> {

    private Context context;
    private List<Pharmacy> pharmacyList;
    private List<Pharmacy> originalList;

    public PharmacyAdapter(Context context, List<Pharmacy> pharmacyList) {
        this.context = context;
        this.pharmacyList = new ArrayList<>(pharmacyList);
        this.originalList = new ArrayList<>(pharmacyList);
    }

    @NonNull
    @Override
    public PharmacyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context)
                .inflate(R.layout.item_pharmacy, parent, false);

        return new PharmacyViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull PharmacyViewHolder holder, int position) {

        Pharmacy pharmacy = pharmacyList.get(position);

        holder.txtName.setText(pharmacy.getName());
        holder.txtAddress.setText(pharmacy.getAddress());
        holder.txtOwner.setText(pharmacy.getOwnerName());
        holder.imgOwner.setImageResource(pharmacy.getOwnerPhoto());

        // Click on owner profile
        holder.ownerProfile.setOnClickListener(v -> {

            Intent intent = new Intent(context, OwnerProfileActivity.class);

            intent.putExtra("ownerName", pharmacy.getOwnerName());
            intent.putExtra("ownerEmail", pharmacy.getOwnerEmail());
            intent.putExtra("ownerPhone", pharmacy.getOwnerPhone());
            intent.putExtra("pharmacyName", pharmacy.getName());

            context.startActivity(intent);
        });

        // Pharmacy details
        holder.btnDetails.setOnClickListener(v -> {

            Intent intent = new Intent(context, PharmacyDetailsActivity.class);

            intent.putExtra("pharmacyName", pharmacy.getName());
            intent.putExtra("address", pharmacy.getAddress());
            intent.putExtra("ownerName", pharmacy.getOwnerName());

            context.startActivity(intent);
        });

        // Edit pharmacy
        holder.btnEdit.setOnClickListener(v -> {

            Intent intent = new Intent(context, EditPharmacyActivity.class);

            intent.putExtra("pharmacyName", pharmacy.getName());
            intent.putExtra("address", pharmacy.getAddress());

            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return pharmacyList.size();
    }

    public void filter(String text) {

        pharmacyList.clear();

        if (text == null || text.trim().isEmpty()) {
            pharmacyList.addAll(originalList);
        } else {

            String search = text.toLowerCase().trim();

            for (Pharmacy pharmacy : originalList) {

                if (pharmacy.getName().toLowerCase().contains(search)
                        || pharmacy.getAddress().toLowerCase().contains(search)
                        || pharmacy.getOwnerName().toLowerCase().contains(search)) {

                    pharmacyList.add(pharmacy);
                }
            }
        }

        notifyDataSetChanged();
    }

    public static class PharmacyViewHolder extends RecyclerView.ViewHolder {

        TextView txtName, txtAddress, txtOwner;
        ImageView imgOwner;
        View ownerProfile;
        Button btnDetails, btnEdit;

        public PharmacyViewHolder(@NonNull View itemView) {
            super(itemView);

            txtName = itemView.findViewById(R.id.txtPharmacyName);
            txtAddress = itemView.findViewById(R.id.txtAddress);
            txtOwner = itemView.findViewById(R.id.txtOwnerName);
            imgOwner = itemView.findViewById(R.id.imgOwner);

            ownerProfile = itemView.findViewById(R.id.ownerProfile);

            btnDetails = itemView.findViewById(R.id.btnDetails);
            btnEdit = itemView.findViewById(R.id.btnEdit);
        }
    }
}
