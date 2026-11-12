
package com.example.myapplication;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

public class AdminLoginActivity extends AppCompatActivity {

    EditText etEmail, etPassword;
    Button btnLogin;
    TextView tvSignup;
    DatabaseHelper dbHelper;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_admin_login);

        dbHelper = new DatabaseHelper(this);

        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);
        btnLogin = findViewById(R.id.btnLogin);
        tvSignup = findViewById(R.id.tvSignup);

        btnLogin.setOnClickListener(v -> login());

        tvSignup.setOnClickListener(v -> {
            Intent intent = new Intent(
                    AdminLoginActivity.this,
                    SignupActivity.class);
            startActivity(intent);
        });
    }

    private void login() {

        String email = etEmail.getText().toString().trim();
        String password = etPassword.getText().toString();

        if (email.isEmpty() || password.isEmpty()) {
            Toast.makeText(this,
                    "Veuillez remplir tous les champs",
                    Toast.LENGTH_SHORT).show();
            return;
        }

        if (dbHelper.checkAdmin(email, password)) {
            startActivity(new Intent(this,
                    AdminDashboardActivity.class));
            finish();

        } else if (dbHelper.checkPharmacist(email, password)) {

            long pharmacyId = dbHelper.getPharmacyIdByOwnerEmail(email);
            if (pharmacyId == -1) {
                Toast.makeText(this,
                        "Votre compte n'est pas encore associé à une pharmacie",
                        Toast.LENGTH_LONG).show();
                return;
            }

            Intent intent = new Intent(this,
                    PharmacistDashboardActivity.class);
            intent.putExtra("pharmacy_id", pharmacyId);
            startActivity(intent);
            finish();

        } else {
            Toast.makeText(this,
                    "Email ou mot de passe incorrect, ou compte en attente",
                    Toast.LENGTH_LONG).show();
        }
    }
}
