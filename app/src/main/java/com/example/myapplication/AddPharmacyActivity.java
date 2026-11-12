package com.example.myapplication;

import android.os.Bundle;
import android.util.Patterns;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class AddPharmacyActivity extends AppCompatActivity {

    private EditText edtNewName;
    private EditText edtNewAddress;
    private EditText edtNewOwner;
    private EditText edtNewEmail;
    private EditText edtNewPhone;

    private DatabaseHelper dbHelper;

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

        Button btnCreatePharmacy = findViewById(R.id.btnCreatePharmacy);

        btnCreatePharmacy.setOnClickListener(v -> addPharmacy());
    }

    private void addPharmacy() {

        String name = edtNewName.getText().toString().trim();
        String address = edtNewAddress.getText().toString().trim();
        String owner = edtNewOwner.getText().toString().trim();
        String email = edtNewEmail.getText().toString().trim();
        String phone = edtNewPhone.getText().toString().trim();

        if (name.isEmpty() || address.isEmpty()
                || owner.isEmpty() || email.isEmpty() || phone.isEmpty()) {

            Toast.makeText(
                    this,
                    "Veuillez remplir tous les champs",
                    Toast.LENGTH_SHORT
            ).show();
            return;
        }

        if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            edtNewEmail.setError("Adresse email invalide");
            return;
        }

        long result = dbHelper.addPharmacy(
                name,
                address,
                owner,
                email,
                phone
        );

        if (result != -1) {

            Toast.makeText(
                    this,
                    "Pharmacie ajoutée avec succès",
                    Toast.LENGTH_SHORT
            ).show();

            setResult(RESULT_OK);
            finish();

        } else {
            Toast.makeText(
                    this,
                    "Impossible d'ajouter la pharmacie. Vérifiez l'email du propriétaire.",
                    Toast.LENGTH_LONG
            ).show();
        }
    }
}