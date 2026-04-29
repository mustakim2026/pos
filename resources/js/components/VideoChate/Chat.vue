<script setup>
import { ref, onMounted } from 'vue';

const localVideo = ref(null);
const remoteVideo = ref(null);
const isMuted = ref(false);
const isCameraOff = ref(false);

// 1. Access Camera and Microphone
const startStream = async () => {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({
            video: true,
            audio: true
        });

        // Attach the local stream to the <video> element
        if (localVideo.value) {
            localVideo.value.srcObject = stream;
        }
    } catch (error) {
        console.error("Error accessing media devices.", error);
    }
};

// 2. Toggle Controls
const toggleMic = () => {
    const stream = localVideo.value.srcObject;
    stream.getAudioTracks()[0].enabled = !stream.getAudioTracks()[0].enabled;
    isMuted.value = !isMuted.value;
};

const toggleCamera = () => {
    const stream = localVideo.value.srcObject;
    stream.getVideoTracks()[0].enabled = !stream.getVideoTracks()[0].enabled;
    isCameraOff.value = !isCameraOff.value;
};

onMounted(() => {
    startStream();
});
</script>

<template>
    <div class="video-app">
        <!-- Sidebar (Participants & Chat) -->
        <aside class="sidebar">
            <div class="logo">V-CHAT</div>
            <!-- ... (Previous Sidebar HTML) ... -->
        </aside>

        <!-- Main Video Screen -->
        <main class="main-screen">
            <div class="video-grid">
                <!-- LOCAL VIDEO -->
                <div class="video-tile">
                    <video ref="localVideo" autoplay playsinline muted></video>
                    <div class="name-tag">You {{ isMuted ? '(Muted)' : '' }}</div>
                </div>

                <!-- REMOTE VIDEO (Placeholder for now) -->
                <div class="video-tile active-speaker">
                    <video ref="remoteVideo" autoplay playsinline></video>
                    <div class="name-tag">Alice Smith</div>
                </div>
            </div>

            <!-- Controls -->
            <div class="controls">
                <button @click="toggleMic" :class="['btn-icon', { 'disabled': isMuted }]">
                    {{ isMuted ? '🔇' : '🎤' }}
                </button>
                <button @click="toggleCamera" :class="['btn-icon', { 'disabled': isCameraOff }]">
                    {{ isCameraOff ? '❌📹' : '📹' }}
                </button>
                <button class="btn-icon hangup">📞</button>
            </div>
        </main>
    </div>
</template>

<style scoped>
/* Include the CSS provided in the previous response here */
.disabled {
    background: #555 !important;
    opacity: 0.6;
}

video {
    transform: scaleX(-1);
    /* Mirror effect for local video */
}
</style>
