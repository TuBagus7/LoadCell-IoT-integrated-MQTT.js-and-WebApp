const uniqueClientId = "mqttjs_" + Math.random().toString(16).substr(2, 8);
const host = "wss://broker.emqx.io:8084/mqtt";
const options = {
  keepalive: 60,
  reschedulePings: true,
  protocolId: "MQTT",
  protocolVersion: 4,
  reconnectPeriod: 1000,
  connectTimeout: 30 * 1000,
  clean: true,
  clientId: uniqueClientId,
};

console.log("Menghubungkan ke Broker");
const client = mqtt.connect(host, options);

client.on("connect", () => {
  console.log("Terhubung");
  const statusElement = document.getElementById("status");
  if (statusElement) {
    statusElement.innerHTML = "Terhubung";
    statusElement.style.color = "blue";
  }

  client.subscribe("loadcell/#", { qos: 1 });
});

client.on("message", function (topic, payload) {
  const payloadString = payload.toString();

  if (topic === "loadcell/berat") {
    const beratElement = document.getElementById("berat");
    if (beratElement) {
      beratElement.innerHTML = payloadString;
    }
  } else if (topic === "loadcell/jarak") {
    const usonicElement = document.getElementById("usonic");
    if (usonicElement) {
      usonicElement.innerHTML = payloadString;
    }
  } else if (topic === "loadcell/result") {
    const resultElement = document.getElementById("result");
    if (resultElement) {
      resultElement.innerHTML = payloadString;
    }
  }

  if (topic.includes("monitoring/status/")) {
    const monitoringElement = document.getElementById(topic);
    if (monitoringElement) {
      monitoringElement.innerHTML = payloadString;

      if (payloadString === "offline") {
        monitoringElement.style.color = "red";
      } else if (payloadString === "online") {
        monitoringElement.style.color = "blue";
      }
    }
  }
});

function publishLampu() {
  let data = "mati";
  if (document.getElementById("lampu1-nyala").checked) {
    data = "nyala";
  }

  client.publish("monitoring/12345678/led", data, { qos: 1, retain: true });
}
