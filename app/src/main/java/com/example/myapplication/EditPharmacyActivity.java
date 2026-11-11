package com.example.myapplication;


import android.os.Bundle;
import android.widget.EditText;
import android.widget.Button;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class EditPharmacyActivity extends AppCompatActivity {

    EditText edtName, edtAddress;
    Button btnSave;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_edit_pharmacy);

        edtName = findViewById(R.id.edtPharmacyName);
        edtAddress = findViewById(R.id.edtPharmacyAddress);
        btnSave = findViewById(R.id.btnSavePharmacy);

        edtName.setText(getIntent().getStringExtra("pharmacyName"));
        edtAddress.setText(getIntent().getStringExtra("address"));

        btnSave.setOnClickListener(v -> {

            String name = edtName.getText().toString().trim();
            String address = edtAddress.getText().toString().trim();

            if (name.isEmpty() || address.isEmpty()) {
                Toast.makeText(this,
                        "Veuillez remplir tous les champs",
                        Toast.LENGTH_SHORT).show();
                return;
            }

            Toast.makeText(this,
                    "Modifications prêtes : " + name,
                    Toast.LENGTH_SHORT).show();

            finish();
        });
    }
}
