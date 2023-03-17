package com.example.telephone;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;
import android.widget.TextView;

import java.util.List;

public class ItemHumeurAdapter extends ArrayAdapter<ItemHumeur> {

    private int identifiantVueItem;
    private LayoutInflater inflater;

    static class SauvegardeTextView {
        TextView emoji;
        TextView libelle;
        TextView date;
    }

    public ItemHumeurAdapter(Context contexte, int vueItem,
                             List<ItemHumeur> lesItems) {
        super(contexte, vueItem, lesItems);
        this.identifiantVueItem = vueItem;
        inflater = (LayoutInflater)getContext()
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public View getView(int position, View uneVue, ViewGroup parent) {

        ItemHumeur item = getItem(position);
        LinearLayout vueItemListe;
        SauvegardeTextView sauve;

        if (uneVue == null) {
            uneVue = inflater.inflate(identifiantVueItem, parent, false);
            sauve = new SauvegardeTextView();
            sauve.emoji = uneVue.findViewById(R.id.emoji);
            sauve.libelle = uneVue.findViewById(R.id.libelle);
            sauve.date = uneVue.findViewById(R.id.date);
            uneVue.setTag(sauve);

        } else {
            sauve = (SauvegardeTextView) uneVue.getTag();
        }

        sauve.emoji.setText(item.getEmoji());
        sauve.libelle.setText(item.getLibelle());
        sauve.date.setText(item.getDate());

        return uneVue;
    }
}
