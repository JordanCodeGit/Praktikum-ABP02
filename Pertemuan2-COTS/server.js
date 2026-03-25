// ============================================================
// server.js - Backend Express.js (Asynchronous & Validated)
// Pokédex CRUD App — Node.js + Express
// ============================================================

const express = require('express');
const fs = require('fs').promises; // Menggunakan asinkron
const path = require('path');

const app = express();
const PORT = 3000;
const DATA_FILE = path.join(__dirname, 'data.json');

app.use(express.json());
app.use(express.static(path.join(__dirname, 'public')));

// ============================================================
// FUNGSI HELPER (Asynchronous)
// ============================================================

async function readData() {
  try {
    const raw = await fs.readFile(DATA_FILE, 'utf-8');
    return JSON.parse(raw);
  } catch (error) {
    if (error.code === 'ENOENT') {
      await fs.writeFile(DATA_FILE, '[]');
      return [];
    }
    throw error;
  }
}

async function writeData(data) {
  await fs.writeFile(DATA_FILE, JSON.stringify(data, null, 2));
}

// ============================================================
// REST API ENDPOINTS
// ============================================================

// GET /api/pokemon - Ambil semua data Pokémon
app.get('/api/pokemon', async (req, res) => {
  try {
    const pokemon = await readData();
    res.json(pokemon);
  } catch (error) {
    res.status(500).json({ message: 'Terjadi kesalahan saat membaca data server.' });
  }
});

// POST /api/pokemon - Tambah Pokémon baru
app.post('/api/pokemon', async (req, res) => {
  try {
    const { name, type, ability, hp, region } = req.body;

    // Backend Validation
    if (!name || !type || !ability || !hp || !region) {
      return res.status(400).json({ message: 'Data ditolak: Semua field wajib diisi.' });
    }

    const pokemonList = await readData();
    const newId = pokemonList.length > 0 ? Math.max(...pokemonList.map(p => p.id)) + 1 : 1;
    
    const newPokemon = { 
      id: newId, 
      name, 
      type, 
      ability, 
      hp: parseInt(hp) || 0, 
      region 
    };
    
    pokemonList.push(newPokemon);
    await writeData(pokemonList);
    
    res.status(201).json({ message: 'Pokémon berhasil didaftarkan!', pokemon: newPokemon });
  } catch (error) {
    res.status(500).json({ message: 'Terjadi kesalahan saat menyimpan data.' });
  }
});

// PUT /api/pokemon/:id - Update data Pokémon berdasarkan ID
app.put('/api/pokemon/:id', async (req, res) => {
  try {
    const { name, type, ability, hp, region } = req.body;

    // Backend Validation
    if (!name || !type || !ability || !hp || !region) {
      return res.status(400).json({ message: 'Data ditolak: Semua field wajib diisi.' });
    }

    const pokemonList = await readData();
    const id = parseInt(req.params.id);
    const index = pokemonList.findIndex(p => p.id === id);

    if (index === -1) {
      return res.status(404).json({ message: 'Update gagal: Pokémon tidak ditemukan.' });
    }

    pokemonList[index] = { 
      id, 
      name, 
      type, 
      ability, 
      hp: parseInt(hp) || 0, 
      region 
    };
    
    await writeData(pokemonList);
    res.json({ message: 'Data Pokémon berhasil diperbarui!', pokemon: pokemonList[index] });
  } catch (error) {
    res.status(500).json({ message: 'Terjadi kesalahan saat memperbarui data.' });
  }
});

// DELETE /api/pokemon/:id - Hapus Pokémon berdasarkan ID
app.delete('/api/pokemon/:id', async (req, res) => {
  try {
    const pokemonList = await readData();
    const id = parseInt(req.params.id);
    const filtered = pokemonList.filter(p => p.id !== id);

    if (filtered.length === pokemonList.length) {
      return res.status(404).json({ message: 'Hapus gagal: Pokémon tidak ditemukan.' });
    }

    await writeData(filtered);
    res.json({ message: 'Pokémon berhasil dilepaskan!' });
  } catch (error) {
    res.status(500).json({ message: 'Terjadi kesalahan saat menghapus data.' });
  }
});

// ============================================================
// JALANKAN SERVER
// ============================================================

app.listen(PORT, () => {
  console.log(`✅ Server Pokédex berjalan asinkron di http://localhost:${PORT}`);
});