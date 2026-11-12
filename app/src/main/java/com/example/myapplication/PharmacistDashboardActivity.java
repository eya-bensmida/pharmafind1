
package com.example.myapplication;

import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class PharmacistDashboardActivity extends AppCompatActivity {

    private TextView txtPharmacistName;
    private TextView txtPharmacyName;
    private TextView txtTotalMedicines;
    private TextView txtAvailableMedicines;
    private TextView txtUnavailableMedicines;

    private Button btnManageMedicines;
    private Button btnAddMedicine;
    private Button btnPharmacistProfile;

    private DatabaseHelper databaseHelper;

    private long pharmacyId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pharmacist_dashboard);

        txtPharmacistName = findViewById(R.id.txtPharmacistName);
        txtPharmacyName = findViewById(R.id.txtPharmacyName);
        txtTotalMedicines = findViewById(R.id.txtTotalMedicines);
        txtAvailableMedicines = findViewById(R.id.txtAvailableMedicines);
        txtUnavailableMedicines = findViewById(R.id.txtUnavailableMedicines);

        btnManageMedicines = findViewById(R.id.btnManageMedicines);
        btnAddMedicine = findViewById(R.id.btnAddMedicine);
        btnPharmacistProfile = findViewById(R.id.btnPharmacistProfile);

        databaseHelper = new DatabaseHelper(this);

        pharmacyId = getIntent().getLongExtra("pharmacy_id", -1);

        loadDashboard();

        btnAddMedicine.setOnClickListener(v -> {
            Intent intent = new Intent(
                    PharmacistDashboardActivity.this,
                    AddMedicineActivity.class
            );

            intent.putExtra("pharmacy_id", pharmacyId);
            startActivity(intent);
        });

        btnManageMedicines.setOnClickListener(v -> {
            Intent intent = new Intent(
                    PharmacistDashboardActivity.this,
                    ManageMedicinesActivity.class
            );

            intent.putExtra("pharmacy_id", pharmacyId);
            startActivity(intent);
        });

        // AJOUT : Ouvrir l'écran du profil pharmacien
        btnPharmacistProfile.setOnClickListener(v -> {

            Intent intent = new Intent(
                    PharmacistDashboardActivity.this,
                    PharmacistProfileActivity.class
            );

            intent.putExtra("pharmacy_id", pharmacyId);

            startActivity(intent);
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
        loadDashboard();
    }

    private void loadDashboard() {

        if (pharmacyId <= 0) {
            txtTotalMedicines.setText("0");
            txtAvailableMedicines.setText("0");
            txtUnavailableMedicines.setText("0");

            Toast.makeText(this,
                    "Identifiant de pharmacie introuvable",
                    Toast.LENGTH_LONG).show();
            return;
        }

        SQLiteDatabase db = databaseHelper.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT " +
                        "COUNT(*) AS total, " +
                        "SUM(CASE WHEN quantity > 0 THEN 1 ELSE 0 END) AS available, " +
                        "SUM(CASE WHEN quantity = 0 THEN 1 ELSE 0 END) AS unavailable " +
                        "FROM pharmacy_medicaments " +
                        "WHERE pharmacy_id = ?",
                new String[]{String.valueOf(pharmacyId)}
        );

        if (cursor.moveToFirst()) {

            int total = cursor.getInt(0);
            int available = cursor.getInt(1);
            int unavailable = cursor.getInt(2);

            txtTotalMedicines.setText(String.valueOf(total));
            txtAvailableMedicines.setText(String.valueOf(available));
            txtUnavailableMedicines.setText(String.valueOf(unavailable));
        }

        cursor.close();

        // Récupérer le nom de la pharmacie et du pharmacien
        Cursor pharmacyCursor = db.rawQuery(
                "SELECT p.name, u.full_name " +
                        "FROM pharmacies p " +
                        "INNER JOIN users u ON p.owner_id = u.id " +
                        "WHERE p.id = ?",
                new String[]{String.valueOf(pharmacyId)}
        );

        if (pharmacyCursor.moveToFirst()) {

            String pharmacyName = pharmacyCursor.getString(0);
            String pharmacistName = pharmacyCursor.getString(1);

            txtPharmacyName.setText(pharmacyName);
            txtPharmacistName.setText(pharmacistName);

        } else {

            txtPharmacyName.setText("Pharmacie inconnue");
            txtPharmacistName.setText("Pharmacien");
        }

        pharmacyCursor.close();
    }
}
