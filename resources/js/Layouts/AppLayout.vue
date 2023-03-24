<template>
  <w-app>
    <Head :title="title" />
    <jet-banner />
    <div>
      <div class="flex flex-1 max-h-screen overflow-hidden">
        <MobileMenu :sidebarOpen="sidebarOpen" @switchToTeam="switchToTeam" />
        <MiniMenu
          :sidebarOpen="sidebarOpen"
          @sidebarOpenChange="sidebarOpenChange"
        />
        <HeaderMenu
          :sidebarOpen="sidebarOpen"
          @sidebarOpenChange="sidebarOpenChange"
          @logout="logout"
          :alertItems="alerts"
          @clearJobAlerts="clearJobAlerts"
        >
          <template #header>
            <slot name="header"></slot>
          </template>
          <slot></slot>
        </HeaderMenu>
      </div>
    </div>
  </w-app>
</template>

<script setup>
import MobileMenu from '@/Layouts/Elements/MobileMenu.vue'
import MiniMenu from '@/Layouts/Elements/MiniMenu.vue'
import HeaderMenu from '@/Layouts/Elements/HeaderMenu.vue'

import JetBanner from '@/Jetstream/Banner.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'

import { sidebarOpen } from '@/VueComposable/store'
import { Inertia } from '@inertiajs/inertia'
import { useStore } from 'vuex'
import { computed } from 'vue'

const store = useStore()

const props = defineProps(['title'])

const sidebarOpenChange = status => {
  sidebarOpen.value = status
}

const switchToTeam = team => {
  Inertia.put(
    route('current-team.update'),
    {
      team_id: team.id,
    },
    {
      preserveState: false,
    },
  )
}

const logout = () => {
  Inertia.post(route('logout'))
}

const alerts = computed(() => {
  return store.state.UserAlerts.alerts
})

const clearJobAlerts = async () => {
  axios.get(route('users.clear_alerts')).then(res => {
    if (res.status === 200) {
      store.dispatch('update_alerts', [])
    }
  })
}
</script>

<style lang="scss">
.splitpanes__pane {
  box-shadow: 0 0 3px rgba(0, 0, 0, 0.1) inset;
  .splitpanes__pane {
    box-shadow: none;
  }
}

em.specs {
  font-size: 0.2em;
  line-height: 1;
  position: absolute;
  color: #bbb;
  bottom: 0.5em;
  left: 0;
  right: 0;
  text-align: center;
}

.splitpanes__splitter {
  touch-action: none;
  background-color: #fff;
  box-sizing: border-box;
  position: relative;
  flex-shrink: 0;
  &:before,
  &:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    background-color: rgba(0, 0, 0, 0.15);
    transition: background-color 0.3s;
  }
  &:hover:before,
  &:hover:after {
    background-color: rgba(0, 0, 0, 0.25);
  }
  &:first-child {
    cursor: auto;
  }
}

.splitpanes .splitpanes .splitpanes__splitter {
  z-index: 1;
}
.splitpanes--vertical > .splitpanes__splitter,
.splitpanes--vertical > .splitpanes__splitter {
  width: 7px;
  border-left: 1px solid #eee;
  margin-left: -1px;
  &:before,
  &:after {
    transform: translateY(-50%);
    width: 1px;
    height: 30px;
  }
  &:before {
    margin-left: -2px;
  }
  &:after {
    margin-left: 1px;
  }
}
.splitpanes--horizontal > .splitpanes__splitter,
.splitpanes--horizontal > .splitpanes__splitter {
  height: 7px;
  border-top: 1px solid #eee;
  margin-top: -1px;
  &:before,
  &:after {
    transform: translateX(-50%);
    width: 30px;
    height: 1px;
  }
  &:before {
    margin-top: -2px;
  }
  &:after {
    margin-top: 1px;
  }
}
</style>

<style lang="scss" scoped>
.min-width {
  min-width: 16rem;
}
.extra-small {
  font-size: 10px;
}
</style>
