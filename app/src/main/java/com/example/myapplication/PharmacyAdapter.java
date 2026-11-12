package com.example.myapplication;

import android.app.AlertDialog;
import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;

public class PharmacyAdapter extends RecyclerView.Adapter<PharmacyAdapter.PharmacyViewHolder> {

    private final Context context;
    private final DatabaseHelper dbHelper;

    private final ArrayList<Pharmacy> pharmacyList;
    private final ArrayList<Pharmacy> originalList;

    public PharmacyAdapter(Context context, ArrayList<Pharmacy> list) {
        this.context = context;
        this.dbHelper = new DatabaseHelper(context);

        this.pharmacyList = new ArrayList<>(list);
        this.originalList = new ArrayList<>(list);
    }

    @NonNull
    @Override
    public PharmacyViewHolder onCreateViewHolder(
            @NonNull ViewGroup parent, int viewType) {

        View view = LayoutInflater.from(context).inflate(
                R.layout.item_pharmacy,
                parent,
                false
        );

        return new PharmacyViewHolder(view);
    }

    @Override
    public void onBindViewHolder(
            @NonNull PharmacyViewHolder holder, int position) {

        Pharmacy pharmacy = pharmacyList.get(position);

        holder.txtPharmacyName.setText(pharmacy.getName());
        holder.txtAddress.setText(pharmacy.getAddress());
        holder.txtOwnerName.setText(pharmacy.getOwnerName());

        holder.imgOwner.setImageResource(pharmacy.getOwnerPhoto());

        // Ouvrir le profil du propriétaire
        holder.ownerProfile.setOnClickListener(v -> {

            Intent intent = new Intent(
                    context,
                    OwnerProfileActivity.class
            );

            intent.putExtra("ownerName", pharmacy.getOwnerName());
            intent.putExtra("ownerEmail", pharmacy.getOwnerEmail());
            intent.putExtra("ownerPhone", pharmacy.getOwnerPhone());
            intent.putExtra("pharmacyName", pharmacy.getName());

            context.startActivity(intent);
        });

        // Modifier la pharmacie
        holder.btnEdit.setOnClickListener(v -> {

            Intent intent = new Intent(
                    context,
                    EditPharmacyActivity.class
            );

            intent.putExtra("pharmacyId", pharmacy.getId());
            intent.putExtra("pharmacyName", pharmacy.getName());
            intent.putExtra("address", pharmacy.getAddress());

            context.startActivity(intent);
        });

        // Supprimer la pharmacie
        holder.btnDelete.setOnClickListener(v -> {

            new AlertDialog.Builder(context)
                    .setTitle("Supprimer la pharmacie")
                    .setMessage(
                            "Voulez-vous vraiment supprimer " +
                                    pharmacy.getName() + " ?"
                    )
                    .setPositiveButton("Supprimer", (dialog, which) -> {

                        boolean deleted = dbHelper.deletePharmacy(
                                pharmacy.getId()
                        );

                        if (deleted) {
                            removePharmacy(pharmacy.getId());

                            Toast.makeText(
                                    context,
                                    "Pharmacie supprimée",
                                    Toast.LENGTH_SHORT
                            ).show();

                        } else {
                            Toast.makeText(
                                    context,
                                    "Erreur lors de la suppression",
                                    Toast.LENGTH_SHORT
                            ).show();
                        }
                    })
                    .setNegativeButton("Annuler", null)
                    .show();
        });
    }

    private void removePharmacy(long id) {

        for (int i = pharmacyList.size() - 1; i >= 0; i--) {
            if (pharmacyList.get(i).getId() == id) {
                pharmacyList.remove(i);
                notifyItemRemoved(i);
                break;
            }
        }

        for (int i = originalList.size() - 1; i >= 0; i--) {
            if (originalList.get(i).getId() == id) {
                originalList.remove(i);
                break;
            }
        }
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

    public void updateList(ArrayList<Pharmacy> newList) {
        originalList.clear();
        originalList.addAll(newList);

        pharmacyList.clear();
        pharmacyList.addAll(newList);

        notifyDataSetChanged();
    }

    @Override
    public int getItemCount() {
        return pharmacyList.size();
    }

    static class PharmacyViewHolder extends RecyclerView.ViewHolder {

        TextView txtPharmacyName;
        TextView txtAddress;
        TextView txtOwnerName;

        ImageView imgOwner;
        LinearLayout ownerProfile;

        Button btnEdit;
        Button btnDelete;

        public PharmacyViewHolder(@NonNull View itemView) {
            super(itemView);

            txtPharmacyName = itemView.findViewById(R.id.txtPharmacyName);
            txtAddress = itemView.findViewById(R.id.txtAddress);
            txtOwnerName = itemView.findViewById(R.id.txtOwnerName);

            imgOwner = itemView.findViewById(R.id.imgOwner);
            ownerProfile = itemView.findViewById(R.id.ownerProfile);

            btnEdit = itemView.findViewById(R.id.btnEdit);
            btnDelete = itemView.findViewById(R.id.btnDelete);
        }
    }
}