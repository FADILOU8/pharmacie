<div class="field">
    <label class="label" for="name">Nom de la pharmacie</label>
    <input id="name" name="name" value="{{ old('name', $pharmacy->name ?? '') }}" class="input" required>
    @error('name')<div class="error-text">{{ $message }}</div>@enderror
</div>
<div class="field">
    <label class="label" for="address">Adresse</label>
    <input id="address" name="address" value="{{ old('address', $pharmacy->address ?? '') }}" class="input" required>
    @error('address')<div class="error-text">{{ $message }}</div>@enderror
</div>
<div class="field">
    <label class="label" for="city">Ville</label>
    <input id="city" name="city" value="{{ old('city', $pharmacy->city ?? '') }}" class="input" required>
    @error('city')<div class="error-text">{{ $message }}</div>@enderror
</div>
<div class="field">
    <label class="label" for="phone">Téléphone</label>
    <input id="phone" name="phone" value="{{ old('phone', $pharmacy->phone ?? '') }}" class="input">
    @error('phone')<div class="error-text">{{ $message }}</div>@enderror
</div>
<div class="field">
    <label class="label" for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email', $pharmacy->email ?? '') }}" class="input">
    @error('email')<div class="error-text">{{ $message }}</div>@enderror
</div>
<div class="field">
    <label class="label" for="open_hours">Horaires d'ouverture</label>
    <input id="open_hours" name="open_hours" value="{{ old('open_hours', $pharmacy->open_hours ?? '') }}" class="input">
    @error('open_hours')<div class="error-text">{{ $message }}</div>@enderror
</div>
<div class="actions-row">
    <button class="button success" type="submit">{{ $buttonText }}</button>
    <a href="{{ route('pharmacies.index') }}" class="button secondary">Retour</a>
</div>
