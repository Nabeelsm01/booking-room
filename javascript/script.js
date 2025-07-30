const startButton = document.getElementById('startButton');
const joinButton = document.getElementById('joinButton');
const stopButton = document.getElementById('stopButton');
const localVideo = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');

let localStream;
let peerConnection;
const config = { iceServers: [{ urls: 'stun:stun.l.google.com:19302' }] };

startButton.onclick = startMeeting;
joinButton.onclick = joinMeeting;
stopButton.onclick = stopMeeting;

const socket = new WebSocket('ws://localhost:8080');

socket.onmessage = async (event) => {
    const message = JSON.parse(event.data);

    if (message.type === 'offer') {
        await peerConnection.setRemoteDescription(new RTCSessionDescription(message.offer));
        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);
        sendToServer({ type: 'answer', answer: answer });
    } else if (message.type === 'answer') {
        await peerConnection.setRemoteDescription(new RTCSessionDescription(message.answer));
    } else if (message.type === 'candidate') {
        await peerConnection.addIceCandidate(new RTCIceCandidate(message.candidate));
    }
};

async function startMeeting() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        localVideo.srcObject = localStream;

        peerConnection = new RTCPeerConnection(config);

        localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

        peerConnection.ontrack = event => {
            remoteVideo.srcObject = event.streams[0];
        };

        peerConnection.onicecandidate = event => {
            if (event.candidate) {
                sendToServer({ type: 'candidate', candidate: event.candidate });
            }
        };

        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);

        sendToServer({ type: 'offer', offer: offer });

    } catch (error) {
        console.error('Error starting the meeting.', error);
    }
}

async function joinMeeting() {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
        remoteVideo.srcObject = localStream;

        peerConnection = new RTCPeerConnection(config);

        localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));

        peerConnection.ontrack = event => {
            remoteVideo.srcObject = event.streams[0];
        };

        peerConnection.onicecandidate = event => {
            if (event.candidate) {
                sendToServer({ type: 'candidate', candidate: event.candidate });
            }
        };

    } catch (error) {
        console.error('Error joining the meeting.', error);
    }
}

function stopMeeting() {
    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }

    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
        localVideo.srcObject = null;
        remoteVideo.srcObject = null;
    }
}

function sendToServer(message) {
    socket.send(JSON.stringify(message));
}
