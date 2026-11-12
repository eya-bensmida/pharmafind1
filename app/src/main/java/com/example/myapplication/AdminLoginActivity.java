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

        if (dbHelper.checkAdmin(email, password)) {

            Intent intent = new Intent(
                    AdminLoginActivity.this,
                    AdminDashboardActivity.class
            );

            startActivity(intent);
            finish();

        } else {
            Toast.makeText(
                    this,
                    "Email ou mot de passe incorrect",
                    Toast.LENGTH_SHORT
            ).show();
        }
    }
}