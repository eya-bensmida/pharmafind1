package com.example.myapplication;

import android.os.Bundle;
import android.util.Patterns;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class AddPharmacyActivity extends AppCompatActivity {

    EditText edtNewName, edtNewAddress;
    EditText edtNewOwner, edtNewEmail, edtNewPhone;

    Button btnCreatePharmacy;

    DatabaseHelper dbHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_add_pharmacy);

        dbHelper = new DatabaseHelper(this);

        edtNewName = findViewById(R.id.edtNewName);
        edtNewAddress = findViewById(R.id.edtNewAddress);
        edtNewOwner = findViewById(R.id.edtNewOwner);
        edtNewEmail = findViewById(R.id.edtNewEmail);
        edtNewPhone = findViewById(R.id.edtNewPhone);

        btnCreatePharmacy = findViewById(R.id.btnCreatePharmacy);

        btnCreatePharmacy.setOnClickListener(v -> createPharmacy());
    }

    private void createPharmacy() {

        String name = edtNewName.getText().toString().trim();
        String address = edtNewAddress.getText().toString().trim();
        String owner = edtNewOwner.getText().toString().trim();
        String email = edtNewEmail.getText().toString().trim();
        String phone = edtNewPhone.getText().toString().trim();

        if (name.isEmpty() || address.isEmpty() ||
                owner.isEmpty() || email.isEmpty() || phone.isEmpty()) {

            Toast.makeText(this,
                    "Veuillez remplir tous les champs",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            edtNewEmail.setError("Email invalide");
            return;
        }

        long pharmacyId = dbHelper.addPharmacy(
                name, address, owner, email, phone
        );
        if (pharmacyId != -1) {
            Toast.makeText(this, "Pharmacie ajoutée avec succès !", Toast.LENGTH_SHORT).show();
            finish();
        } else {
            Toast.makeText(this,
                    "Erreur : vérifie le nom de la pharmacie et l'email du pharmacien.",
                    Toast.LENGTH_LONG).show();
        }
    }
}
