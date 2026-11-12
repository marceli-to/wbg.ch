<template>
  <div class="notifications" aria-live="polite">
    <TransitionGroup name="notification">
      <div
        v-for="n in notifications"
        :key="n.id"
        :class="['notification', n.type]"
        role="status"
        @click="dismiss(n.id)"
      >
        <div class="notification-content">{{ n.text }}</div>
      </div>
    </TransitionGroup>
  </div>
</template>
<script setup>
import { notifications, dismiss } from '@/lib/notify';
</script>
