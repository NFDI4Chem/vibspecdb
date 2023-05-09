<template>
  <!-- Spectral Viewer section -->
  <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-40 lg:px-8" id="projects">
    <div class="mx-auto max-w-2xl lg:mx-0">
      <h2 class="text-3xl font-bold tracking-tight text-cyan-500 sm:text-4xl">
        Main Dashboard View
      </h2>
    </div>
    <p class="mt-6 text-lg leading-8 text-gray-300">
      The main working window contains the left navigation menu for moving
      between the main pages. Search, link to the documentation page and
      personal account in the top menu. Ability to navigate using the project
      tree between projects and studies. Convenient view in cards for projects
      and studies with a creation date, publicity status and standard preview
      image.
    </p>
    <div class="relative isolate -z-10 mt-5">
      <div class="h-auto w-full">
        <img
          src="/imgs/welcome/Dashboard.png"
          alt=""
          class="w-auto mx-auto shadow-xl"
        />
      </div>
      <div
        class="absolute inset-x-0 -top-16 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl"
        aria-hidden="true"
      >
        <div
          class="aspect-[1.6] w-[82.375rem] flex-none bg-gradient-to-r from-[#80caff] to-[#1f3d56] opacity-25"
          style="
            clip-path: polygon(
              73.6% 51.7%,
              91.7% 11.8%,
              100% 46.4%,
              97.4% 82.2%,
              92.5% 84.9%,
              75.7% 64%,
              55.3% 47.5%,
              46.5% 49.4%,
              45% 62.9%,
              50.3% 87.2%,
              21.3% 64.1%,
              0.1% 100%,
              5.4% 51.1%,
              21.4% 63.9%,
              58.9% 0.2%,
              73.6% 51.7%
            );
          "
        />
      </div>
    </div>
  </div>
</template>

<script setup></script>

<style lang="scss" scoped></style>
