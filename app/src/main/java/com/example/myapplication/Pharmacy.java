package com.example.myapplication;

public class Pharmacy {

    private long id;
    private String name;
    private String address;
    private String ownerName;
    private String ownerEmail;
    private String ownerPhone;
    private int ownerPhoto;

    public Pharmacy(long id, String name, String address,
                    String ownerName, String ownerEmail,
                    String ownerPhone, int ownerPhoto) {
        this.id = id;
        this.name = name;
        this.address = address;
        this.ownerName = ownerName;
        this.ownerEmail = ownerEmail;
        this.ownerPhone = ownerPhone;
        this.ownerPhoto = ownerPhoto;
    }

    // Constructeur compatible avec les anciens appels
    public Pharmacy(String name, String address,
                    String ownerName, String ownerEmail,
                    String ownerPhone, int ownerPhoto) {
        this(0, name, address, ownerName, ownerEmail,
                ownerPhone, ownerPhoto);
    }

    public long getId() {
        return id;
    }

    public String getName() {
        return name;
    }

    public String getAddress() {
        return address;
    }

    public String getOwnerName() {
        return ownerName;
    }

    public String getOwnerEmail() {
        return ownerEmail;
    }

    public String getOwnerPhone() {
        return ownerPhone;
    }

    public int getOwnerPhoto() {
        return ownerPhoto;
    }
}