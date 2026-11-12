
package com.example.myapplication;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import java.util.ArrayList;

public class DatabaseHelper extends SQLiteOpenHelper {

    private static final String DATABASE_NAME = "pharmafind.db";
    private static final int DATABASE_VERSION = 1;

    public DatabaseHelper(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    @Override
    public void onConfigure(SQLiteDatabase db) {
        super.onConfigure(db);
        db.setForeignKeyConstraintsEnabled(true);
    }

    @Override
    public void onCreate(SQLiteDatabase db) {

        // Création de la table des utilisateurs
        db.execSQL(
                "CREATE TABLE users (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "full_name TEXT NOT NULL, " +
                        "email TEXT NOT NULL UNIQUE, " +
                        "password TEXT NOT NULL, " +
                        "phone TEXT, " +
                        "role TEXT NOT NULL, " +
                        "status TEXT DEFAULT 'active')"
        );

        // Création de la table des pharmacies
        db.execSQL(
                "CREATE TABLE pharmacies (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "owner_id INTEGER NOT NULL, " +
                        "name TEXT NOT NULL, " +
                        "address TEXT NOT NULL, " +
                        "FOREIGN KEY(owner_id) REFERENCES users(id))"
        );

        // Création de la table des médicaments
        db.execSQL(
                "CREATE TABLE medicaments (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "name TEXT NOT NULL, " +
                        "dosage TEXT NOT NULL, " +
                        "category TEXT, " +
                        "description TEXT)"
        );

        // Création de la table d'association pharmacie-médicaments
        db.execSQL(
                "CREATE TABLE pharmacy_medicaments (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "pharmacy_id INTEGER NOT NULL, " +
                        "medicament_id INTEGER NOT NULL, " +
                        "quantity INTEGER DEFAULT 0, " +
                        "FOREIGN KEY(pharmacy_id) REFERENCES pharmacies(id), " +
                        "FOREIGN KEY(medicament_id) REFERENCES medicaments(id))"
        );

        // Création de la table des recherches infructueuses
        db.execSQL(
                "CREATE TABLE failed_searches (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "searched_medicine TEXT NOT NULL, " +
                        "created_at TEXT DEFAULT CURRENT_TIMESTAMP)"
        );

        // Compte administrateur de démonstration
        ContentValues admin = new ContentValues();
        admin.put("full_name", "Administrateur");
        admin.put("email", "admin@pharmafind.com");
        admin.put("password", "Admin123");
        admin.put("phone", "");
        admin.put("role", "admin");
        admin.put("status", "active");

        db.insert("users", null, admin);

        // AJOUT 1 : Créer le compte pharmacien de démonstration
        ContentValues pharmacist = new ContentValues();
        pharmacist.put("full_name", "Pharmacien Test");
        pharmacist.put("email", "pharmacien@pharmafind.com");
        pharmacist.put("password", "Pharma123");
        pharmacist.put("phone", "0600000000");
        pharmacist.put("role", "owner");
        pharmacist.put("status", "active");

        long pharmacistId = db.insert("users", null, pharmacist);

        // AJOUT 2 : Créer une pharmacie associée au compte pharmacien
        if (pharmacistId != -1) {

            ContentValues pharmacy = new ContentValues();
            pharmacy.put("owner_id", pharmacistId);
            pharmacy.put("name", "Pharmacie Test");
            pharmacy.put("address", "Centre-ville");

            db.insert("pharmacies", null, pharmacy);
        }

        // Médicament de démonstration : Doliprane
        ContentValues med1 = new ContentValues();
        med1.put("name", "Doliprane");
        med1.put("dosage", "1000 mg");
        med1.put("category", "Antalgique");
        med1.put("description", "Médicament de démonstration");

        long med1Id = db.insert("medicaments", null, med1);

        // Médicament de démonstration : Augmentin
        ContentValues med2 = new ContentValues();
        med2.put("name", "Augmentin");
        med2.put("dosage", "1 g");
        med2.put("category", "Antibiotique");
        med2.put("description", "Médicament de démonstration");

        long med2Id = db.insert("medicaments", null, med2);

        // AJOUT 3 : Associer les médicaments de démonstration à la pharmacie
        if (pharmacistId != -1) {

            Cursor pharmacyCursor = db.rawQuery(
                    "SELECT id FROM pharmacies WHERE owner_id = ?",
                    new String[]{String.valueOf(pharmacistId)}
            );

            if (pharmacyCursor.moveToFirst()) {

                long pharmacyId = pharmacyCursor.getLong(0);

                // Associer Doliprane à la pharmacie
                if (med1Id != -1) {
                    ContentValues association1 = new ContentValues();
                    association1.put("pharmacy_id", pharmacyId);
                    association1.put("medicament_id", med1Id);
                    association1.put("quantity", 10);

                    db.insert("pharmacy_medicaments", null, association1);
                }

                // Associer Augmentin à la pharmacie
                if (med2Id != -1) {
                    ContentValues association2 = new ContentValues();
                    association2.put("pharmacy_id", pharmacyId);
                    association2.put("medicament_id", med2Id);
                    association2.put("quantity", 5);

                    db.insert("pharmacy_medicaments", null, association2);
                }
            }

            pharmacyCursor.close();
        }
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        // Aucune migration nécessaire pour la version actuelle.
    }

    // Vérifier la connexion de l'administrateur
    public boolean checkAdmin(String email, String password) {

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT id FROM users " +
                        "WHERE email = ? AND password = ? " +
                        "AND role = 'admin' AND status = 'active'",
                new String[]{email, password}
        );

        boolean result = cursor.moveToFirst();
        cursor.close();

        return result;
    }

    // AJOUT 4 : Vérifier la connexion du pharmacien
    public boolean checkPharmacist(String email, String password) {

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT id FROM users " +
                        "WHERE email = ? AND password = ? " +
                        "AND role = 'owner' AND status = 'active'",
                new String[]{email, password}
        );

        boolean result = cursor.moveToFirst();
        cursor.close();

        return result;
    }

    // AJOUT 5 : Récupérer l'identifiant de la pharmacie par email du propriétaire
    public long getPharmacyIdByOwnerEmail(String email) {

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT p.id FROM pharmacies p " +
                        "INNER JOIN users u ON p.owner_id = u.id " +
                        "WHERE u.email = ? AND u.role = 'owner'",
                new String[]{email}
        );

        long pharmacyId = -1;

        if (cursor.moveToFirst()) {
            pharmacyId = cursor.getLong(0);
        }

        cursor.close();

        return pharmacyId;
    }

    // Ajouter une pharmacie et son propriétaire
    public long addPharmacy(String name, String address,
                            String ownerName, String email, String phone) {

        SQLiteDatabase db = this.getWritableDatabase();
        long pharmacyId = -1;

        db.beginTransaction();

        try {
            long ownerId;

            Cursor cursor = db.rawQuery(
                    "SELECT id, role FROM users WHERE email = ?",
                    new String[]{email}
            );

            if (cursor.moveToFirst()) {

                ownerId = cursor.getLong(0);
                String role = cursor.getString(1);

                cursor.close();

                if (!"owner".equalsIgnoreCase(role)) {
                    throw new Exception(
                            "Cet email est déjà utilisé par un autre type de compte."
                    );
                }

                ContentValues ownerValues = new ContentValues();
                ownerValues.put("full_name", ownerName);
                ownerValues.put("phone", phone);

                db.update(
                        "users",
                        ownerValues,
                        "id = ?",
                        new String[]{String.valueOf(ownerId)}
                );

            } else {

                cursor.close();

                ContentValues owner = new ContentValues();
                owner.put("full_name", ownerName);
                owner.put("email", email);
                owner.put("password", "NON_DEFINI");
                owner.put("phone", phone);
                owner.put("role", "owner");
                owner.put("status", "active");

                ownerId = db.insert("users", null, owner);

                if (ownerId == -1) {
                    throw new Exception("Impossible de créer le propriétaire.");
                }
            }

            ContentValues pharmacy = new ContentValues();
            pharmacy.put("owner_id", ownerId);
            pharmacy.put("name", name);
            pharmacy.put("address", address);

            pharmacyId = db.insert("pharmacies", null, pharmacy);

            if (pharmacyId == -1) {
                throw new Exception("Impossible d'ajouter la pharmacie.");
            }

            db.setTransactionSuccessful();

        } catch (Exception e) {
            e.printStackTrace();
            pharmacyId = -1;

        } finally {
            db.endTransaction();
        }

        return pharmacyId;
    }

    // Récupérer toutes les pharmacies avec les informations du propriétaire
    public ArrayList<Pharmacy> getAllPharmacies() {

        ArrayList<Pharmacy> list = new ArrayList<>();
        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT p.id, p.name, p.address, " +
                        "u.full_name, u.email, u.phone " +
                        "FROM pharmacies p " +
                        "INNER JOIN users u ON p.owner_id = u.id " +
                        "ORDER BY p.id DESC",
                null
        );

        if (cursor.moveToFirst()) {
            do {
                long id = cursor.getLong(0);
                String name = cursor.getString(1);
                String address = cursor.getString(2);
                String ownerName = cursor.getString(3);
                String ownerEmail = cursor.getString(4);
                String ownerPhone = cursor.getString(5);

                list.add(new Pharmacy(
                        id,
                        name,
                        address,
                        ownerName,
                        ownerEmail,
                        ownerPhone,
                        android.R.drawable.ic_menu_myplaces
                ));

            } while (cursor.moveToNext());
        }

        cursor.close();
        return list;
    }

    // Modifier une pharmacie
    public boolean updatePharmacy(long id, String name, String address) {

        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();
        values.put("name", name);
        values.put("address", address);

        int rows = db.update(
                "pharmacies",
                values,
                "id = ?",
                new String[]{String.valueOf(id)}
        );

        return rows > 0;
    }

    // Supprimer une pharmacie
    public boolean deletePharmacy(long id) {

        SQLiteDatabase db = this.getWritableDatabase();
        db.beginTransaction();

        try {
            // Supprimer d'abord les associations avec les médicaments
            db.delete(
                    "pharmacy_medicaments",
                    "pharmacy_id = ?",
                    new String[]{String.valueOf(id)}
            );

            int rows = db.delete(
                    "pharmacies",
                    "id = ?",
                    new String[]{String.valueOf(id)}
            );

            if (rows > 0) {
                db.setTransactionSuccessful();
                return true;
            }

            return false;

        } finally {
            db.endTransaction();
        }
    }

    // Enregistrer une recherche infructueuse
    public boolean recordFailedSearch(String medicineName) {

        if (medicineName == null || medicineName.trim().isEmpty()) {
            return false;
        }

        ContentValues values = new ContentValues();
        values.put("searched_medicine", medicineName.trim());

        SQLiteDatabase db = this.getWritableDatabase();

        return db.insert("failed_searches", null, values) != -1;
    }

    // Récupérer les médicaments recherchés et le nombre de recherches
    public ArrayList<String> getFailedSearchesSummary() {

        ArrayList<String> list = new ArrayList<>();
        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT searched_medicine, COUNT(*) AS total " +
                        "FROM failed_searches " +
                        "GROUP BY searched_medicine " +
                        "ORDER BY total DESC",
                null
        );

        if (cursor.moveToFirst()) {
            do {
                String medicine = cursor.getString(0);
                int total = cursor.getInt(1);

                list.add(medicine + " — " + total + " recherche(s)");

            } while (cursor.moveToNext());
        }

        cursor.close();
        return list;
    }

    // Nombre total de recherches infructueuses
    public int getFailedSearchesCount() {

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT COUNT(*) FROM failed_searches",
                null
        );

        int count = 0;

        if (cursor.moveToFirst()) {
            count = cursor.getInt(0);
        }

        cursor.close();
        return count;
    }
}
