
package com.example.myapplication;

import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.GradientDrawable;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

public class ManageMedicinesActivity extends AppCompatActivity {

    private TextView txtMedicineCount;
    private TextView txtEmptyMedicines;
    private LinearLayout layoutMedicineList;

    private DatabaseHelper databaseHelper;
    private long pharmacyId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_manage_medicines);

        databaseHelper = new DatabaseHelper(this);

        pharmacyId = getIntent().getLongExtra("pharmacy_id", -1);

        txtMedicineCount = findViewById(R.id.txtMedicineCount);
        txtEmptyMedicines = findViewById(R.id.txtEmptyMedicines);
        layoutMedicineList = findViewById(R.id.layoutMedicineList);

        if (pharmacyId <= 0) {
            Toast.makeText(this,
                    "Identifiant de pharmacie invalide",
                    Toast.LENGTH_LONG).show();
            finish();
            return;
        }

        loadMedicines();
    }

    @Override
    protected void onResume() {
        super.onResume();

        if (databaseHelper != null && pharmacyId > 0
                && layoutMedicineList != null) {
            loadMedicines();
        }
    }

    // AJOUT 1 : Charger tous les médicaments de la pharmacie
    private void loadMedicines() {

        layoutMedicineList.removeAllViews();

        SQLiteDatabase db = databaseHelper.getReadableDatabase();

        String sql =
                "SELECT m.id, m.name, m.dosage, m.category, " +
                        "m.description, pm.quantity " +
                        "FROM medicaments m " +
                        "INNER JOIN pharmacy_medicaments pm " +
                        "ON m.id = pm.medicament_id " +
                        "WHERE pm.pharmacy_id = ? " +
                        "ORDER BY m.name ASC";

        try (Cursor cursor = db.rawQuery(
                sql,
                new String[]{String.valueOf(pharmacyId)}
        )) {

            int count = cursor.getCount();

            txtMedicineCount.setText(
                    count + (count > 1
                            ? " médicaments"
                            : " médicament")
            );

            txtEmptyMedicines.setVisibility(
                    count == 0 ? View.VISIBLE : View.GONE
            );

            while (cursor.moveToNext()) {

                long medicineId = cursor.getLong(0);
                String name = cursor.getString(1);
                String dosage = cursor.getString(2);
                String category = cursor.getString(3);
                String description = cursor.getString(4);
                int quantity = cursor.getInt(5);

                addMedicineCard(
                        medicineId,
                        name,
                        dosage,
                        category,
                        description,
                        quantity
                );
            }
        }
    }

    // AJOUT 2 : Créer une carte pour chaque médicament
    private void addMedicineCard(
            long medicineId,
            String name,
            String dosage,
            String category,
            String description,
            int quantity) {

        LinearLayout card = new LinearLayout(this);
        card.setOrientation(LinearLayout.VERTICAL);
        card.setPadding(18, 18, 18, 18);

        GradientDrawable background = new GradientDrawable();
        background.setColor(Color.WHITE);
        background.setCornerRadius(18);

        card.setBackground(background);
        card.setElevation(3);

        LinearLayout.LayoutParams cardParams =
                new LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT
                );

        cardParams.setMargins(0, 0, 0, 15);
        card.setLayoutParams(cardParams);

        TextView txtName = new TextView(this);
        txtName.setText(name);
        txtName.setTextColor(Color.rgb(38, 53, 44));
        txtName.setTextSize(19);
        txtName.setTypeface(null, Typeface.BOLD);
        card.addView(txtName);

        addInfo(card, "Dosage : " + dosage);

        addInfo(card, "Catégorie : " +
                (category == null ? "" : category));

        if (description != null && !description.trim().isEmpty()) {
            addInfo(card, "Description : " + description);
        }

        addInfo(card, "Quantité en stock : " + quantity);

        TextView txtStatus = new TextView(this);

        if (quantity > 0) {
            txtStatus.setText("Disponible");
            txtStatus.setTextColor(Color.rgb(33, 140, 88));
        } else {
            txtStatus.setText("Indisponible");
            txtStatus.setTextColor(Color.rgb(214, 91, 85));
        }

        txtStatus.setTextSize(14);
        txtStatus.setTypeface(null, Typeface.BOLD);

        LinearLayout.LayoutParams statusParams =
                new LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.WRAP_CONTENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT
                );

        statusParams.setMargins(0, 10, 0, 10);
        txtStatus.setLayoutParams(statusParams);
        card.addView(txtStatus);

        // AJOUT 3 : Ligne des boutons
        LinearLayout buttonLayout = new LinearLayout(this);
        buttonLayout.setOrientation(LinearLayout.HORIZONTAL);
        buttonLayout.setWeightSum(2);

        // AJOUT 4 : Bouton Modifier
        Button btnEdit = new Button(this);
        btnEdit.setText("Modifier");
        btnEdit.setTextColor(Color.WHITE);
        btnEdit.setAllCaps(false);

        btnEdit.setBackgroundTintList(
                android.content.res.ColorStateList.valueOf(
                        Color.rgb(33, 140, 88)
                )
        );

        LinearLayout.LayoutParams editParams =
                new LinearLayout.LayoutParams(
                        0,
                        LinearLayout.LayoutParams.WRAP_CONTENT,
                        1
                );

        editParams.setMargins(0, 0, 6, 0);
        btnEdit.setLayoutParams(editParams);

        btnEdit.setOnClickListener(v -> {

            Intent intent = new Intent(
                    ManageMedicinesActivity.this,
                    EditMedicineActivity.class
            );

            intent.putExtra("medicine_id", medicineId);
            intent.putExtra("pharmacy_id", pharmacyId);

            startActivity(intent);
        });

        buttonLayout.addView(btnEdit);

        // AJOUT 5 : Bouton Retirer
        Button btnDelete = new Button(this);
        btnDelete.setText("Retirer");
        btnDelete.setTextColor(Color.WHITE);
        btnDelete.setAllCaps(false);

        btnDelete.setBackgroundTintList(
                android.content.res.ColorStateList.valueOf(
                        Color.rgb(214, 91, 85)
                )
        );

        LinearLayout.LayoutParams deleteParams =
                new LinearLayout.LayoutParams(
                        0,
                        LinearLayout.LayoutParams.WRAP_CONTENT,
                        1
                );

        deleteParams.setMargins(6, 0, 0, 0);
        btnDelete.setLayoutParams(deleteParams);

        btnDelete.setOnClickListener(v ->
                confirmDelete(medicineId, name)
        );

        buttonLayout.addView(btnDelete);

        card.addView(buttonLayout);
        layoutMedicineList.addView(card);
    }

    // AJOUT 6 : Afficher les informations
    private void addInfo(LinearLayout card, String text) {

        TextView textView = new TextView(this);
        textView.setText(text);
        textView.setTextColor(Color.rgb(113, 128, 120));
        textView.setTextSize(14);

        LinearLayout.LayoutParams params =
                new LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT
                );

        params.setMargins(0, 8, 0, 0);
        textView.setLayoutParams(params);

        card.addView(textView);
    }

    // AJOUT 7 : Retirer le médicament de cette pharmacie
    private void confirmDelete(long medicineId, String name) {

        new AlertDialog.Builder(this)
                .setTitle("Retirer le médicament")
                .setMessage(
                        "Voulez-vous retirer " + name +
                                " de votre pharmacie ?"
                )
                .setPositiveButton("Oui", (dialog, which) -> {

                    SQLiteDatabase db =
                            databaseHelper.getWritableDatabase();

                    int rows = db.delete(
                            "pharmacy_medicaments",
                            "pharmacy_id = ? AND medicament_id = ?",
                            new String[]{
                                    String.valueOf(pharmacyId),
                                    String.valueOf(medicineId)
                            }
                    );

                    if (rows > 0) {

                        Toast.makeText(this,
                                "Médicament retiré de votre pharmacie",
                                Toast.LENGTH_SHORT).show();

                        loadMedicines();

                    } else {

                        Toast.makeText(this,
                                "Erreur lors de la suppression",
                                Toast.LENGTH_SHORT).show();
                    }
                })
                .setNegativeButton("Annuler", null)
                .show();
    }
}
