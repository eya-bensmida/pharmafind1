package com.example.myapplication;

import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class EditPharmacyActivity extends AppCompatActivity {

    private EditText edtPharmacyName;
    private EditText edtPharmacyAddress;
    private Button btnSavePharmacy;

    private DatabaseHelper dbHelper;
    private long pharmacyId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_pharmacy);

        dbHelper = new DatabaseHelper(this);

        edtPharmacyName = findViewById(R.id.edtPharmacyName);
        edtPharmacyAddress = findViewById(R.id.edtPharmacyAddress);
        btnSavePharmacy = findViewById(R.id.btnSavePharmacy);

        pharmacyId = getIntent().getLongExtra("pharmacyId", -1);

        String name = getIntent().getStringExtra("pharmacyName");
        String address = getIntent().getStringExtra("address");

        edtPharmacyName.setText(name);
        edtPharmacyAddress.setText(address);

        btnSavePharmacy.setOnClickListener(v -> saveChanges());
    }

    private void saveChanges() {

        String name = edtPharmacyName.getText().toString().trim();
        String address = edtPharmacyAddress.getText().toString().trim();

        if (name.isEmpty() || address.isEmpty()) {
            Toast.makeText(
                    this,
                    "Veuillez remplir tous les champs",
                    Toast.LENGTH_SHORT
            ).show();
            return;
        }

        if (pharmacyId == -1) {
            Toast.makeText(
                    this,
                    "Identifiant de pharmacie introuvable",
                    Toast.LENGTH_SHORT
            ).show();
            return;
        }

        boolean updated = dbHelper.updatePharmacy(
                pharmacyId,
                name,
                address
        );

        if (updated) {
            Toast.makeText(
                    this,
                    "Pharmacie modifiée avec succès",
                    Toast.LENGTH_SHORT
            ).show();

            setResult(RESULT_OK);
            finish();

        } else {
            Toast.makeText(
                    this,
                    "Erreur lors de la modification",
                    Toast.LENGTH_SHORT
            ).show();
        }
    }
}