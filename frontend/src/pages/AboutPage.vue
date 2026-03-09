<template>
  <GreenImagePanelLayout title="O aplikaciji" :image-src="pocetnaSlika">
    <p>
      Piljarnica NOVA je web aplikacija koja omogućuje kupcima da, u nedostatku
      vremena za fizički posjet trgovini, kupnju obave brzo i jednostavno iz doma
      ili ureda.
    </p>

    <p class="mt-4">
      U ponudi su pretežno domaće namirnice lokalnih OPG-ova, dok dio
      vansezonskog asortimana može biti uvoz.
    </p>

    <h3 class="text-h6 mt-6 mb-2">Dostava</h3>
    <p>
      Dostava je <strong>besplatna</strong> i odnosi se na <strong>područje grada</strong>.
    </p>

    <h3 class="text-h6 mt-6 mb-2">Kako funkcionira</h3>
    <ol>
      <li>Odaberi kategoriju i proizvode</li>
      <li>Dodaj u košaricu i unesi podatke za dostavu</li>
      <li>Potvrdi narudžbu i pričekaj dostavu na adresu</li>
    </ol>

    <div class="mt-8 d-flex justify-end">
      <v-btn variant="text" to="/">Natrag na početnu</v-btn>
    </div>
  </GreenImagePanelLayout>
</template>

<script setup lang="ts">
  import GreenImagePanelLayout from '@/components/GreenImagePanelLayout.vue'
  import pocetnaSlika from '@/assets/pocetnaSlika.png'
</script>
