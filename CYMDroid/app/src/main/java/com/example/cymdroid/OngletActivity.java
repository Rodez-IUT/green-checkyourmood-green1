package com.example.cymdroid;

import android.os.Bundle;

import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

import androidx.appcompat.app.AppCompatActivity;
import androidx.viewpager2.widget.ViewPager2;

public class OngletActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_onglet);
        /*
         * on récupère un accès sur le ViewPager défini dans la vue
         * ainsi que sur le TabLayout qui gèrera les onglets
         */
        ViewPager2 gestionnairePagination = findViewById(R.id.activity_main_viewpager);
        TabLayout gestionnaireOnglet = findViewById(R.id.tab_layout);
        /*
         * on associe au ViewPager un adaptateur (c'est lui qui organise le défilement
         * entre les fragments à afficher)
         */
        gestionnairePagination.setAdapter(new AdaptateurPage(this)) ;
        /*
         * On regroupe dans un tableau les intitulés des boutons d'onglet
         */
        String[] titreOnglet = {getString(R.string.onglet_alea),
                                getString(R.string.onglet_en_cours_1),
                                getString(R.string.onglet_en_cours_2)};

        /*
         * On crée une instance de type TabLayoutMediator qui fera le lien entre
         * le gestionnaire de pagination et le gestionnaire des onglets
         * La méthode onConfigureTab permet de préciser quel initulé de bouton d'onglets
         * correspond à tel ou tel onglet, selon la position de celui-ci
         * L'instance TabLayoutMediator est attachée à l'activité courante
         *
         */
        new TabLayoutMediator(gestionnaireOnglet, gestionnairePagination,
                new TabLayoutMediator.TabConfigurationStrategy() {
                    @Override public void onConfigureTab(TabLayout.Tab tab, int position) {
                        tab.setText(titreOnglet[position]);
                    }
                }).attach();
         /*
         * Le code ci-dessus peut être rendu plus concis de la manière suivante :
         new TabLayoutMediator(tabLayout, pager,
         (tab, position) -> tab.setText(titreOnglet[position])
         ).attach();
         *
         *
         */
    }
}

