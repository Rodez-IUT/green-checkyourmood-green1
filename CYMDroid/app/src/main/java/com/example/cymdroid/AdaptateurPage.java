package com.example.cymdroid;

import androidx.fragment.app.Fragment;
import androidx.viewpager2.adapter.FragmentStateAdapter;

/**
 * Cette classe est un adaptateur pour gérer les fragments qui seront associés
 * au ViewPager.
 * Dans sa version minimale, la classe contient un constructeur auquel on passera en
 * argument l'activité qui gère le ViewPager, une méthode createFragment et une méthode
 * getItemCount
 * @author C.Servières
 */
public class AdaptateurPage extends FragmentStateAdapter {
    /**
     * Nombre de fragments gérés par cet adaptateur
     */
    private static final int NB_FRAGMENT = 2;

    /**
     * Constructeur de base
     *
     * @param activite activité qui contient le ViewPager qui gèrera les fragments
     */
    public AdaptateurPage(OngletActivity activite) {
        super(activite);
    }

    @Override
    public Fragment createFragment(int position) {
        /*
         * Le ViewPager auquel on associera cet adaptateur devra afficher successivement
         * un fragment de type : FragmentUn, puis FragmentDeux, et enfin FragmentTrois
         * C'est dans cette méthode que l'on décide dans quel ordre sont affichés les
         * fragments, et quel fragment doit précisément être affiché
         */
        switch (position) {
            case 0:
                return VisualisationActivity.newInstance();
            case 1:
                return AjoutActivity.newInstance();
            default:
                return null;
        }
    }

    @Override
    public int getItemCount() {
        // renvoyer le nombre de fragments gérés par l'adaptateur
        return NB_FRAGMENT;
    }

}