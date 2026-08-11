<x-layouts.app title="Profil Saya">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="text-xs font-bold uppercase tracking-wider text-orange-400">Pengaturan Akun</div>
            <h1 class="text-3xl font-black text-white mt-1">Profil Member</h1>
            <p class="text-sm text-slate-400 mt-0.5">Kelola identitas akun dan alamat pengantaran konsol Anda.</p>
        </div>
    </div>

    <div class="mt-8 grid gap-8 lg:grid-cols-3">
        <!-- User Badge & Rank Card (Left) -->
        <div class="rounded-3xl border border-white/10 bg-slate-900/90 p-6 flex flex-col justify-between shadow-xl text-center">
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="mx-auto flex h-24 w-24 object-cover rounded-3xl shadow-xl shadow-black/50 border-2 border-white/10">
                @else
                    <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-3xl bg-gradient-to-br from-orange-500 to-amber-600 text-3xl font-black text-white shadow-xl shadow-orange-500/25">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif

                <div x-data="cameraAvatar()" class="mt-4">
                    <button @click="openCamera()" type="button" class="text-xs font-bold text-orange-400 hover:text-orange-300 transition flex items-center justify-center gap-1.5 mx-auto">
                        <i class="fa-solid fa-camera"></i> Ambil Foto via Kamera
                    </button>

                    <!-- Camera Modal -->
                    <div x-show="isOpen" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/80 backdrop-blur-sm p-4">
                        <div class="bg-slate-900 border border-white/10 p-6 rounded-3xl shadow-2xl max-w-sm w-full" @click.away="closeCamera()">
                            <h3 class="text-lg font-bold text-white mb-4">Ambil Foto Profil</h3>
                            
                            <div class="relative bg-black rounded-2xl overflow-hidden aspect-square border border-white/10 mb-4 flex items-center justify-center">
                                <video x-ref="video" class="absolute inset-0 w-full h-full object-cover" autoplay playsinline x-show="!capturedImage"></video>
                                <img x-show="capturedImage" :src="capturedImage" class="absolute inset-0 w-full h-full object-cover">
                                <canvas x-ref="canvas" style="display: none;"></canvas>
                                
                                <div x-show="!stream && !capturedImage" class="text-xs text-slate-500">Meminta akses kamera...</div>
                            </div>

                            <!-- Camera Selection Dropdown -->
                            <div x-show="cameras.length > 1 && !capturedImage" class="mb-4">
                                <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Pilih Kamera</label>
                                <select x-model="selectedCamera" @change="startStream()" class="w-full bg-slate-800 border border-white/10 rounded-xl px-3 py-2 text-sm text-white focus:border-orange-500 focus:outline-none">
                                    <template x-for="camera in cameras" :key="camera.deviceId">
                                        <option :value="camera.deviceId" x-text="camera.label || 'Kamera ' + ($index + 1)"></option>
                                    </template>
                                </select>
                            </div>

                            <form method="POST" action="/profile/avatar">
                                @csrf
                                <input type="hidden" name="avatar_base64" :value="capturedImage">
                                
                                <div class="flex gap-2">
                                    <template x-if="!capturedImage">
                                        <button type="button" @click="capture()" class="flex-1 bg-orange-500 text-white font-bold py-3 rounded-xl hover:bg-orange-600 transition">Jepret Foto</button>
                                    </template>
                                    <template x-if="capturedImage">
                                        <div class="flex flex-1 gap-2">
                                            <button type="button" @click="retake()" class="flex-1 bg-slate-800 text-white font-bold py-3 rounded-xl hover:bg-slate-700 border border-white/5 transition">Ulangi</button>
                                            <button type="submit" class="flex-1 bg-green-500 text-white font-bold py-3 rounded-xl hover:bg-green-600 transition">Simpan</button>
                                        </div>
                                    </template>
                                    <button type="button" @click="closeCamera()" class="px-4 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-700 border border-white/5 transition"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <script>
                    function cameraAvatar() {
                        return {
                            isOpen: false,
                            stream: null,
                            capturedImage: null,
                            cameras: [],
                            selectedCamera: '',
                            async openCamera() {
                                this.isOpen = true;
                                this.capturedImage = null;
                                // Request initial permission to get device labels
                                try {
                                    const tempStream = await navigator.mediaDevices.getUserMedia({ video: true });
                                    tempStream.getTracks().forEach(track => track.stop());
                                } catch (err) {
                                    alert("Gagal mengakses kamera. Pastikan browser memiliki izin.");
                                    this.closeCamera();
                                    return;
                                }

                                await this.loadCameras();
                                if (this.cameras.length > 0) {
                                    this.selectedCamera = this.cameras[0].deviceId;
                                    await this.startStream();
                                }
                            },
                            async loadCameras() {
                                const devices = await navigator.mediaDevices.enumerateDevices();
                                this.cameras = devices.filter(device => device.kind === 'videoinput');
                            },
                            async startStream() {
                                if (this.stream) {
                                    this.stream.getTracks().forEach(track => track.stop());
                                }
                                
                                const constraints = {
                                    video: this.selectedCamera ? { deviceId: { exact: this.selectedCamera } } : { facingMode: "user" }
                                };

                                try {
                                    this.stream = await navigator.mediaDevices.getUserMedia(constraints);
                                    this.$refs.video.srcObject = this.stream;
                                } catch (err) {
                                    alert("Gagal memulai kamera yang dipilih.");
                                }
                            },
                            closeCamera() {
                                this.isOpen = false;
                                if (this.stream) {
                                    this.stream.getTracks().forEach(track => track.stop());
                                    this.stream = null;
                                }
                            },
                            capture() {
                                const video = this.$refs.video;
                                const canvas = this.$refs.canvas;
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
                                this.capturedImage = canvas.toDataURL('image/jpeg', 0.8);
                                this.stream.getTracks().forEach(track => track.stop());
                            },
                            retake() {
                                this.openCamera();
                            }
                        }
                    }
                </script>
                <h2 class="text-xl font-bold text-white mt-4">{{ $user->name }}</h2>
                <p class="text-xs text-slate-400">{{ $user->email }}</p>

                <!-- Rank Badge -->
                <div class="mt-6 rounded-2xl bg-slate-950 p-4 border border-white/5 text-left">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Bakso Rank</span>
                        <span class="text-xl">{{ substr($rank['badge'], 0, 4) }}</span>
                    </div>
                    <div class="text-lg font-black text-white mt-1">{{ $rank['name'] }}</div>
                    <div class="text-xs text-orange-400 mt-0.5">{{ $totalDays }} Total Hari Sewa Kumulatif</div>
                    <p class="text-[11px] text-slate-400 mt-2">{{ $rank['benefit'] }}</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 text-xs text-slate-500">
                Terdaftar sejak {{ $user->created_at->format('d M Y') }}
            </div>
        </div>

        <!-- Edit Profile Form (Right) -->
        <div class="lg:col-span-2 rounded-3xl border border-white/10 bg-slate-900/90 p-6 sm:p-8 shadow-xl">
            <h2 class="text-lg font-bold text-white mb-6">Ubah Informasi Pribadi</h2>

            <form method="POST" action="/profile" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nama Lengkap</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition" required>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Nomor Telepon / WhatsApp</label>
                        <input name="phone" value="{{ old('phone', $user->profile?->phone) }}" placeholder="081234567890" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->profile?->date_of_birth?->format('Y-m-d')) }}" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white focus:border-orange-500 focus:outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">Alamat Lengkap Pengantaran (Domisili)</label>
                    <textarea name="address" rows="3" placeholder="Alamat rumah lengkap untuk keperluan delivery konsol" class="w-full rounded-xl border border-white/10 bg-slate-950 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-orange-500 focus:outline-none transition">{{ old('address', $user->profile?->address) }}</textarea>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-end">
                    <button type="submit" class="rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-orange-500/25 hover:bg-orange-600 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
