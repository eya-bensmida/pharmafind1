package com.example.myapplication;

import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class PharmacistProfileActivity extends AppCompatActivity {

    private TextView txtProfileEmail;

    private EditText edtProfileName;
    private EditText edtProfilePhone;
    private EditText edtProfilePharmacyName;
    private EditText edtProfilePharmacyAddress;

    private Button btnSaveProfile;

    private DatabaseHelper databaseHelper;

    private long pharmacyId;
    private long ownerId = -1;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pharmacist_profile);

        // Initialiser les composants XML
        txtProfileEmail = findViewById(R.id.txtProfileEmail);

        edtProfileName = findViewById(R.id.edtProfileName);
        edtProfilePhone = findViewById(R.id.edtProfilePhone);
        edtProfilePharmacyName = findViewById(R.id.edtProfilePharmacyName);
        edtProfilePharmacyAddress = findViewById(R.id.edtProfilePharmacyAddress);

        btnSaveProfile = findViewById(R.id.btnSaveProfile);

        // Initialiser la base de données
        databaseHelper = new DatabaseHelper(this);

        // Récupérer l'identifiant de la pharmacie
        pharmacyId = getIntent().getLongExtra("pharmacy_id", -1);

        loadProfile();

        btnSaveProfile.setOnClickListener(v -> saveProfile());
    }

    // AJOUT 1 : Charger les informations du pharmacien et de sa pharmacie
    private void loadProfile() {

        if (pharmacyId <= 0) {
            Toast.makeText(this,
                    "Identifiant de pharmacie introuvable",
                    Toast.LENGTH_LONG).show();
            finish();
            return;
        }

        SQLiteDatabase db = databaseHelper.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT u.id, u.full_name, u.email, u.phone, " +
                        "p.name, p.address " +
                        "FROM pharmacies p " +
                        "INNER JOIN users u ON p.owner_id = u.id " +
                        "WHERE p.id = ?",
                new String[]{String.valueOf(pharmacyId)}
        );

        if (cursor.moveToFirst()) {

            ownerId = cursor.getLong(0);

            edtProfileName.setText(cursor.getString(1));
            txtProfileEmail.setText(cursor.getString(2));

            String phone = cursor.getString(3);
            edtProfilePhone.setText(phone == null ? "" : phone);

            edtProfilePharmacyName.setText(cursor.getString(4));
            edtProfilePharmacyAddress.setText(cursor.getString(5));

        } else {
            Toast.makeText(this,
                    "Profil introuvable",
                    Toast.LENGTH_LONG).show();
            finish();
        }

        cursor.close();
    }

    // AJOUT 2 : Enregistrer les modifications du profil
    private void saveProfile() {

        String fullName = edtProfileName.getText().toString().trim();
        String phone = edtProfilePhone.getText().toString().trim();
        String pharmacyName = edtProfilePharmacyName.getText().toString().trim();
        String pharmacyAddress = edtProfilePharmacyAddress.getText().toString().trim();

        if (fullName.isEmpty() || phone.isEmpty()
                || pharmacyName.isEmpty() || pharmacyAddress.isEmpty()) {

            Toast.makeText(this,
                    "Veuillez remplir tous les champs",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        if (ownerId <= 0 || pharmacyId <= 0) {
            Toast.makeText(this,
                    "Informations du compte introuvables",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        SQLiteDatabase db = databaseHelper.getWritableDatabase();

        ContentValues userValues = new ContentValues();
        userValues.put("full_name", fullName);
        userValues.put("phone", phone);

        ContentValues pharmacyValues = new ContentValues();
        pharmacyValues.put("name", pharmacyName);
        pharmacyValues.put("address", pharmacyAddress);

        db.beginTransaction();

        try {
            int userUpdated = db.update(
                    "users",
                    userValues,
                    "id = ?",
                    new String[]{String.valueOf(ownerId)}
            );

            int pharmacyUpdated = db.update(
                    "pharmacies",
                    pharmacyValues,
                    "id = ? AND owner_id = ?",
                    new String[]{
                            String.valueOf(pharmacyId),
                            String.valueOf(ownerId)
                    }
            );

            if (userUpdated > 0 && pharmacyUpdated > 0) {
                db.setTransactionSuccessful();

                Toast.makeText(this,
                        "Profil modifié avec succès",
                        Toast.LENGTH_SHORT).show();

                finish();

            } else {
                Toast.makeText(this,
                        "Impossible de modifier le profil",
                        Toast.LENGTH_SHORT).show();
            }

        } finally {
            db.endTransaction();
        }
    }
}
