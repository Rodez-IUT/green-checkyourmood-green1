package com.example.telephone;

import android.app.Activity;
import android.app.AlertDialog;
import android.os.Bundle;
import android.view.Gravity;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.List;

public class VisualisationActivity extends Activity implements AdapterView.OnItemClickListener {
    ListView liste;
    List<ItemHumeur> humeurs;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_visualisation);
        liste = findViewById(R.id.liste);
        humeurs = new ArrayList<>();
        humeurs.add(new ItemHumeur("\uD83E\uDD13", "Adoration", "Ladate", "La description 1"));
        humeurs.add(new ItemHumeur("\uD83E\uDD13", "Adoration", "Ladate", "La description 2"));
        humeurs.add(new ItemHumeur("\uD83E\uDD13", "Adoration", "Ladate", "La description 3"));
        humeurs.add(new ItemHumeur("\uD83E\uDD13", "Adoration", "Ladate", "La description 4"));
        humeurs.add(new ItemHumeur("\uD83E\uDD13", "Adoration", "Ladate", "La description 5"));
        ItemHumeurAdapter item = new ItemHumeurAdapter(this, R.layout.vue_item_liste, humeurs);
        liste.setAdapter(item);
        liste.setOnItemClickListener(this);
    }


    @Override
    public void onItemClick(AdapterView<?> adapterView, View view, int i, long l) {
        onClickMood(i);
    }

    public void onClickMood(int i){
        AlertDialog.Builder builder = new AlertDialog.Builder(this);

        final View customLayout = getLayoutInflater().inflate(R.layout.alert_vue_description, null);
        TextView titre = customLayout.findViewById(R.id.alertTitre);
        titre.setText("Humeur");
        TextView description = customLayout.findViewById(R.id.alertDescription);
        description.setText(humeurs.get(i).getDescription());
        builder.setView(customLayout);
        AlertDialog dialog = builder.create();
        dialog.show();
        dialog.getWindow().setGravity(Gravity.BOTTOM);
        dialog.getWindow().setBackgroundDrawableResource(R.drawable.alerte_description_humeur);
    }
}