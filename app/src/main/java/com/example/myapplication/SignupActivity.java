package com.example.myapplication;

import android.os.Bundle;
import android.util.Patterns;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class SignupActivity extends AppCompatActivity {

    EditText etFirstName, etLastName, etEmail, etPhone;
    EditText etPassword, etConfirmPassword, etPharmacyName;
    Button btnSignup;

    DatabaseHelper dbHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);

        dbHelper = new DatabaseHelper(this);

        etFirstName = findViewById(R.id.etFirstName);
        etLastName = findViewById(R.id.etLastName);
        etEmail = findViewById(R.id.etSignupEmail);
        etPhone = findViewById(R.id.etPhone);
        etPassword = findViewById(R.id.etSignupPassword);
        etConfirmPassword = findViewById(R.id.etConfirmPassword);
        etPharmacyName = findViewById(R.id.etPharmacyName);
        btnSignup = findViewById(R.id.btnSignup);

        btnSignup.setOnClickListener(v -> register());
    }

    private void register() {

        String firstName = etFirstName.getText().toString().trim();
        String lastName = etLastName.getText().toString().trim();
        String email = etEmail.getText().toString().trim();
        String phone = etPhone.getText().toString().trim();
        String password = etPassword.getText().toString();
        String confirm = etConfirmPassword.getText().toString();
        String pharmacy = etPharmacyName.getText().toString().trim();

        if (firstName.isEmpty() || lastName.isEmpty() ||
                email.isEmpty() || phone.isEmpty() ||
                password.isEmpty() || confirm.isEmpty() ||
                pharmacy.isEmpty()) {

            Toast.makeText(this,
                    "Veuillez remplir tous les champs",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        if (!Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            etEmail.setError("Email invalide");
            return;
        }

        if (!password.equals(confirm)) {
            etConfirmPassword.setError("Les mots de passe ne correspondent pas");
            return;
        }

        if (password.length() < 6) {
            etPassword.setError("Minimum 6 caractères");
            return;
        }

        boolean success = dbHelper.registerPharmacist(
                firstName, lastName, email, phone,
                password, pharmacy);

        if (success) {
            Toast.makeText(this,
                    "Inscription réussie ! Votre compte est en attente de validation.",
                    Toast.LENGTH_LONG).show();
            finish();
        } else {
            Toast.makeText(this,
                    "Cet email est déjà utilisé",
                    Toast.LENGTH_LONG).show();
        }
    }
}
