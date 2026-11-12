package com.example.myapplication;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class AdminLoginActivity extends AppCompatActivity {

    private EditText etEmail;
    private EditText etPassword;
    private Button btnLogin;

    private DatabaseHelper dbHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_admin_login);

        dbHelper = new DatabaseHelper(this);

        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);
        btnLogin = findViewById(R.id.btnLogin);

        btnLogin.setOnClickListener(v -> login());
    }

    // Vérifier les identifiants et rediriger selon le rôle
    private void login() {

        String email = etEmail.getText().toString().trim();
        String password = etPassword.getText().toString().trim();

        if (email.isEmpty() || password.isEmpty()) {
            Toast.makeText(
                    this,
                    "Veuillez saisir votre email et votre mot de passe",
                    Toast.LENGTH_SHORT
            ).show();
            return;
        }

        // CONNEXION ADMINISTRATEUR
        if (dbHelper.checkAdmin(email, password)) {

            Intent intent = new Intent(
                    AdminLoginActivity.this,
                    AdminDashboardActivity.class
            );

            startActivity(intent);
            finish();

            return;
        }

        // CONNEXION PHARMACIEN
        if (dbHelper.checkPharmacist(email, password)) {

            // Récupérer l'identifiant de la pharmacie
            long pharmacyId = dbHelper.getPharmacyIdByOwnerEmail(email);

            if (pharmacyId == -1) {

                Toast.makeText(
                        this,
                        "Aucune pharmacie associée à ce compte",
                        Toast.LENGTH_LONG
                ).show();

                return;
            }

            // Ouvrir le dashboard pharmacien
            Intent intent = new Intent(
                    AdminLoginActivity.this,
                    PharmacistDashboardActivity.class
            );

            // Transmettre l'identifiant de la pharmacie
            intent.putExtra("pharmacy_id", pharmacyId);

            startActivity(intent);
            finish();

            return;
        }

        // Identifiants incorrects
        Toast.makeText(
                this,
                "Email ou mot de passe incorrect",
                Toast.LENGTH_SHORT
        ).show();
    }
}