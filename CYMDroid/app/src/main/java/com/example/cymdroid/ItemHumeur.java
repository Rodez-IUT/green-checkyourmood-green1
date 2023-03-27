package com.example.cymdroid;

public class ItemHumeur {

    private String emoji;
    private String libelle;
    private String date;
    private String description;

    public ItemHumeur(String emoji, String libelle, String date, String description) {

        this.emoji = emoji;
        this.libelle = libelle;
        this.date = date;
        this.description = description;
    }

    public String getEmoji() {
        return emoji;
    }

    public String getLibelle() {
        return libelle;
    }

    public String getDate() {
        return date;
    }

    public String getDescription() {
        return description;
    }
}
