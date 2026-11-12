
package com.example.myapplication;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

import java.util.ArrayList;

public class DatabaseHelper extends SQLiteOpenHelper {

    private static final String DATABASE_NAME = "pharmafind.db";
    private static final int DATABASE_VERSION = 3;

    public DatabaseHelper(Context context) {
        super(context, DATABASE_NAME, null, DATABASE_VERSION);
    }

    // =====================================================
    // CONFIGURATION
    // =====================================================

    @Override
    public void onConfigure(SQLiteDatabase db) {
        super.onConfigure(db);
        db.setForeignKeyConstraintsEnabled(true);
    }

    // =====================================================
    // CRÉATION DES TABLES
    // =====================================================

    @Override
    public void onCreate(SQLiteDatabase db) {

        db.execSQL(
                "CREATE TABLE users (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "full_name TEXT NOT NULL, " +
                        "email TEXT NOT NULL UNIQUE, " +
                        "password TEXT NOT NULL, " +
                        "phone TEXT, " +
                        "role TEXT NOT NULL, " +
                        "status TEXT DEFAULT 'active', " +
                        "requested_pharmacy_name TEXT)"
        );

        db.execSQL(
                "CREATE TABLE pharmacies (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "owner_id INTEGER NOT NULL, " +
                        "name TEXT NOT NULL, " +
                        "address TEXT NOT NULL, " +
                        "FOREIGN KEY(owner_id) REFERENCES users(id))"
        );

        db.execSQL(
                "CREATE TABLE medicaments (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "name TEXT NOT NULL, " +
                        "dosage TEXT NOT NULL, " +
                        "category TEXT, " +
                        "description TEXT)"
        );

        db.execSQL(
                "CREATE TABLE pharmacy_medicaments (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "pharmacy_id INTEGER NOT NULL, " +
                        "medicament_id INTEGER NOT NULL, " +
                        "quantity INTEGER DEFAULT 0, " +
                        "FOREIGN KEY(pharmacy_id) REFERENCES pharmacies(id), " +
                        "FOREIGN KEY(medicament_id) REFERENCES medicaments(id))"
        );

        db.execSQL(
                "CREATE TABLE failed_searches (" +
                        "id INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        "searched_medicine TEXT NOT NULL, " +
                        "created_at TEXT DEFAULT CURRENT_TIMESTAMP)"
        );

        // Administrateur de démonstration
        ContentValues admin = new ContentValues();
        admin.put("full_name", "Administrateur");
        admin.put("email", "admin@pharmafind.com");
        admin.put("password", "Admin123");
        admin.put("phone", "");
        admin.put("role", "admin");
        admin.put("status", "active");

        db.insert("users", null, admin);

        // Pharmacien de démonstration
        ContentValues pharmacist = new ContentValues();
        pharmacist.put("full_name", "Pharmacien Test");
        pharmacist.put("email", "pharmacien@pharmafind.com");
        pharmacist.put("password", "Pharma123");
        pharmacist.put("phone", "0600000000");
        pharmacist.put("role", "owner");
        pharmacist.put("status", "active");

        long pharmacistId = db.insert("users", null, pharmacist);

        // Pharmacie de démonstration
        long pharmacyId = -1;

        if (pharmacistId != -1) {

            ContentValues pharmacy = new ContentValues();
            pharmacy.put("owner_id", pharmacistId);
            pharmacy.put("name", "Pharmacie Test");
            pharmacy.put("address", "Centre-ville");

            pharmacyId = db.insert("pharmacies", null, pharmacy);
        }

        // Médicament Doliprane
        ContentValues med1 = new ContentValues();
        med1.put("name", "Doliprane");
        med1.put("dosage", "1000 mg");
        med1.put("category", "Antalgique");
        med1.put("description", "Médicament de démonstration");

        long med1Id = db.insert("medicaments", null, med1);

        // Médicament Augmentin
        ContentValues med2 = new ContentValues();
        med2.put("name", "Augmentin");
        med2.put("dosage", "1 g");
        med2.put("category", "Antibiotique");
        med2.put("description", "Médicament de démonstration");

        long med2Id = db.insert("medicaments", null, med2);

        // Associer les médicaments à la pharmacie de test
        if (pharmacyId != -1) {

            if (med1Id != -1) {
                ContentValues association1 = new ContentValues();
                association1.put("pharmacy_id", pharmacyId);
                association1.put("medicament_id", med1Id);
                association1.put("quantity", 10);

                db.insert("pharmacy_medicaments", null, association1);
            }

            if (med2Id != -1) {
                ContentValues association2 = new ContentValues();
                association2.put("pharmacy_id", pharmacyId);
                association2.put("medicament_id", med2Id);
                association2.put("quantity", 5);

                db.insert("pharmacy_medicaments", null, association2);
            }
        }
    }

    // =====================================================
    // VÉRIFIER SI UNE COLONNE EXISTE
    // =====================================================

    private boolean columnExists(
            SQLiteDatabase db,
            String tableName,
            String columnName) {

        Cursor cursor = db.rawQuery(
                "PRAGMA table_info(" + tableName + ")",
                null
        );

        boolean exists = false;

        try {
            while (cursor.moveToNext()) {

                String column = cursor.getString(
                        cursor.getColumnIndexOrThrow("name")
                );

                if (column.equals(columnName)) {
                    exists = true;
                    break;
                }
            }
        } finally {
            cursor.close();
        }

        return exists;
    }

    // =====================================================
    // MISE À JOUR DE LA BASE DE DONNÉES
    // =====================================================

    @Override
    public void onUpgrade(
            SQLiteDatabase db,
            int oldVersion,
            int newVersion) {

        // Version 1 vers version 2
        if (oldVersion < 2) {

            if (!columnExists(
                    db,
                    "users",
                    "requested_pharmacy_name")) {

                db.execSQL(
                        "ALTER TABLE users " +
                                "ADD COLUMN requested_pharmacy_name TEXT"
                );
            }
        }

        // Version 2 vers version 3
        if (oldVersion < 3) {

            if (!columnExists(db, "medicaments", "category")) {

                db.execSQL(
                        "ALTER TABLE medicaments " +
                                "ADD COLUMN category TEXT"
                );
            }
        }
    }

    // =====================================================
    // INSCRIPTION DU PHARMACIEN
    // =====================================================

    public boolean registerPharmacist(
            String firstName,
            String lastName,
            String email,
            String phone,
            String password,
            String pharmacyName) {

        SQLiteDatabase db = this.getWritableDatabase();

        ContentValues values = new ContentValues();

        values.put(
                "full_name",
                firstName.trim() + " " + lastName.trim()
        );

        values.put("email", email.trim().toLowerCase());
        values.put("password", password);
        values.put("phone", phone.trim());
        values.put("role", "owner");
        values.put("status", "pending");
        values.put("requested_pharmacy_name", pharmacyName.trim());

        long result = db.insert("users", null, values);

        return result != -1;
    }

    // =====================================================
    // CONNEXION ADMINISTRATEUR
    // =====================================================

    public boolean checkAdmin(String email, String password) {

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT id FROM users " +
                        "WHERE email = ? AND password = ? " +
                        "AND role = 'admin' AND status = 'active'",
                new String[]{
                        email.trim().toLowerCase(),
                        password
                }
        );

        boolean result = cursor.moveToFirst();
        cursor.close();

        return result;
    }

    // =====================================================
    // CONNEXION PHARMACIEN
    // =====================================================

    public boolean checkPharmacist(String email, String password) {

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT id FROM users " +
                        "WHERE email = ? AND password = ? " +
                        "AND role = 'owner' AND status = 'active'",
                new String[]{
                        email.trim().toLowerCase(),
                        password
                }
        );

        boolean result = cursor.moveToFirst();
        cursor.close();

        return result;
    }

    // =====================================================
    // RÉCUPÉRER L'ID DE LA PHARMACIE DU PHARMACIEN
    // =====================================================

    public long getPharmacyIdByOwnerEmail(String email) {

        SQLiteDatabase db = this.getReadableDatabase();

        Cursor cursor = db.rawQuery(
                "SELECT p.id FROM pharmacies p " +
                        "INNER JOIN users u ON p.owner_id = u.id " +
                        "WHERE u.email = ? AND u.role = 'owner'",
                new String[]{email.trim().toLowerCase()}
        );

        long pharmacyId = -1;

        if (cursor.moveToFirst()) {
            pharmacyId = cursor.getLong(0);
        }

        cursor.close();

        return pharmacyId;
    }

    // =====================================================
    // AJOUTER UNE PHARMACIE
    // =====================================================

    public long addPharmacy(
            String name,
            String address,
            String ownerName,
            String email,
            String phone) {

        SQLiteDatabase db = this.getWritableDatabase();

        long pharmacyId = -1;

        db.beginTransaction();

        Cursor cursor = null;
        Cursor existing = null;

        try {

            cursor = db.rawQuery(
                    "SELECT id, requested_pharmacy_name, status " +
                            "FROM users WHERE email = ? AND role = 'owner'",
                    new String[]{email.trim().toLowerCase()}
            );

            if (!cursor.moveToFirst()) {
                return -1;
            }

            long ownerId = cursor.getLong(0);
            String requestedName = cursor.getString(1);
            String status = cursor.getString(2);

            cursor.close();
            cursor = null;

            if (requestedName == null ||
                    !requestedName.trim().equalsIgnoreCase(name.trim())) {
                return -1;
            }

            if (!"pending".equalsIgnoreCase(status)) {
                return -1;
            }

            existing = db.rawQuery(
                    "SELECT id FROM pharmacies WHERE owner_id = ?",
                    new String[]{String.valueOf(ownerId)}
            );

            boolean alreadyHasPharmacy = existing.moveToFirst();

            existing.close();
            existing = null;

            if (alreadyHasPharmacy) {
                return -1;
            }

            ContentValues pharmacy = new ContentValues();
            pharmacy.put("owner_id", ownerId);
            pharmacy.put("name", name.trim());
            pharmacy.put("address", address.trim());

            pharmacyId = db.insert("pharmacies", null, pharmacy);

            if (pharmacyId == -1) {
                return -1;
            }

            ContentValues ownerValues = new ContentValues();
            ownerValues.put("full_name", ownerName.trim());
            ownerValues.put("phone", phone.trim());
            ownerValues.put("status", "active");

            int updated = db.update(
                    "users",
                    ownerValues,
                    "id = ?",
                    new String[]{String.valueOf(ownerId)}
            );

            if (updated <= 0) {
                return -1;
            }

            db.setTransactionSuccessful();

            return pharmacyId;

        } catch (Exception e) {

            return -1;

        } finally {

            if (cursor != null) {
                cursor.close();
            }

            if (existing != null) {
                existing.close();
            }

            db.endTransaction();
        }
    }

    // =====================================================
    // RÉCUPÉRER TOUTES LES PHARMACIES
    // =====================================================

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

    // =====================================================
    // MODIFIER UNE PHARMACIE
    // =====================================================

    public boolean updatePharmacy(
            long id,
            String name,
            String address) {

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

    // =====================================================
    // SUPPRIMER UNE PHARMACIE
    // =====================================================

    public boolean deletePharmacy(long id) {

        SQLiteDatabase db = this.getWritableDatabase();

        db.beginTransaction();

        try {

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

    // =====================================================
    // AJOUTER UN MÉDICAMENT ET SON STOCK
    // =====================================================

    public String addMedicine(
            long pharmacyId,
            String name,
            String dosage,
            String category,
            String description,
            int quantity) {

        SQLiteDatabase db = this.getWritableDatabase();

        db.beginTransaction();

        Cursor pharmacyCursor = null;

        try {

            // Vérifier que la pharmacie existe
            pharmacyCursor = db.rawQuery(
                    "SELECT id FROM pharmacies WHERE id = ?",
                    new String[]{String.valueOf(pharmacyId)}
            );

            boolean pharmacyExists = pharmacyCursor.moveToFirst();

            pharmacyCursor.close();
            pharmacyCursor = null;

            if (!pharmacyExists) {
                return "Pharmacie introuvable. ID = " + pharmacyId;
            }

            // Insérer le médicament
            ContentValues medicineValues = new ContentValues();

            medicineValues.put("name", name.trim());
            medicineValues.put("dosage", dosage.trim());
            medicineValues.put("category", category.trim());
            medicineValues.put("description", description.trim());

            long medicineId = db.insertOrThrow(
                    "medicaments",
                    null,
                    medicineValues
            );

            // Insérer le stock
            ContentValues stockValues = new ContentValues();

            stockValues.put("pharmacy_id", pharmacyId);
            stockValues.put("medicament_id", medicineId);
            stockValues.put("quantity", quantity);

            long stockId = db.insertOrThrow(
                    "pharmacy_medicaments",
                    null,
                    stockValues
            );

            if (stockId == -1) {
                return "Impossible d'enregistrer le stock.";
            }

            db.setTransactionSuccessful();

            return "SUCCESS";

        } catch (Exception e) {

            return "Erreur SQLite : " + e.getMessage();

        } finally {

            if (pharmacyCursor != null) {
                pharmacyCursor.close();
            }

            db.endTransaction();
        }
    }

    // =====================================================
    // ENREGISTRER UNE RECHERCHE INFRUCTUEUSE
    // =====================================================

    public boolean recordFailedSearch(String medicineName) {

        if (medicineName == null || medicineName.trim().isEmpty()) {
            return false;
        }

        ContentValues values = new ContentValues();
        values.put("searched_medicine", medicineName.trim());

        SQLiteDatabase db = this.getWritableDatabase();

        return db.insert("failed_searches", null, values) != -1;
    }

    // =====================================================
    // RÉSUMÉ DES RECHERCHES INFRUCTUEUSES
    // =====================================================

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

                list.add(
                        medicine + " — " + total + " recherche(s)"
                );

            } while (cursor.moveToNext());
        }

        cursor.close();

        return list;
    }

    // =====================================================
    // NOMBRE TOTAL DE RECHERCHES INFRUCTUEUSES
    // =====================================================

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
