<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplications;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Get the user's profile photo URL.
     */
    public function getPhoto(Request $request)
    {
        $user = $request->user();

        if ($user->photo) {
            $photoUrl = Storage::url($user->photo);
            return response()->json(['photo' => $photoUrl], 200);
        }

        return response()->json(['photo' => null], 404);
    }

    /**
     * Update user photo
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // 2MB Max
        ]);

        $user = $request->user();
        $photo = $request->file('photo');
        $folderPath = 'profiles/' . $user->id;

        if ($user->photo) {
            Storage::delete($user->photo);
        }

        $path = $photo->store($folderPath, 'public');
        $user->photo = $path;
        $user->save();

        return response()->json(['path' => $path, 'message' => 'Zdjęcie profilowe zaktualizowane.'], 200);
    }

    /**
     * Remove the user's profile photo URL.
     */
    public function removePhoto(Request $request)
    {
        $user = Auth::user();

        if ($user->photo) {
            Storage::delete($user->photo);
            $user->photo = null;
            $user->save();

            return response()->json(['message' => 'Zdjęcie profilowe zostało usunięte.'], 200);
        }

        return response()->json(['message' => 'Brak zdjęcia profilowego do usunięcia.'], 404);
    }

    /**
     * Get the user's CV if exists.
     */
    public function getCv(Request $request)
    {
        $user = $request->user();

        if ($user->cv) {
            return response()->json([
                'exists' => true,
                'path' => Storage::url($user->cv),
                'fileName' => basename($user->cv)
            ],200);
        }

        return response()->json(['exists' => false],404);
    }

    /**
     * Update user's CV.
     */
    public function uploadCv(Request $request)
    {
        $request->validate([
            'cv' => 'required|file|mimes:pdf|max:2048', // 2MB Max, only PDF
        ]);

        $user = $request->user();
        $cv = $request->file('cv');
        $folderPath = 'profiles/' . $user->id;

        if ($user->cv) {
            Storage::delete($user->cv);
        }

        $originalName = $cv->getClientOriginalName();
        $normalizedFileName = $this->normalizeFileName($originalName);

        $path = $cv->storeAs($folderPath, $normalizedFileName, 'public');
        $user->cv = $path;
        $user->save();

        return response()->json(['path' => $path, 'message' => 'CV zostało zaktualizowane.'], 200);
    }

    /**
     * Remove user's CV.
     */
    public function removeCv(Request $request)
    {
        $user = $request->user();
    
        if ($user->cv) {
            Storage::delete($user->cv);
            $user->cv = null;
            $user->save();
    
            return response()->json(['message' => 'CV zostało usunięte.'], 200);
        }
    
        return response()->json(['message' => 'Brak CV do usunięcia.'], 404);
    }
    

    /**
     * Normalizuje nazwę pliku, usuwając polskie znaki i zamieniając spacje na podkreślniki.
     *
     * @param string $fileName Nazwa pliku do normalizacji.
     * @return string Znormalizowana nazwa pliku.
     */
    protected function normalizeFileName(string $fileName): string
    {
        $unwantedArray = [
            'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'Ae',
            'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I',
            'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe', 'Ø'=>'O',
            'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'Ue', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a',
            'â'=>'a', 'ã'=>'a', 'ä'=>'ae', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e',
            'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'oe', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'ue', 'ý'=>'y',
            'þ'=>'b', 'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 'Ą'=>'A', 'ą'=>'a', 'Ć'=>'C', 'ć'=>'c', 'Ę'=>'E',
            'ę'=>'e', 'Ł'=>'L', 'ł'=>'l', 'Ń'=>'N', 'ń'=>'n', 'Ó'=>'O', 'ó'=>'o', 'Ś'=>'S', 'ś'=>'s',
            'Ź'=>'Z', 'ź'=>'z', 'Ż'=>'Z', 'ż'=>'z',
        ];
        $str = strtr($fileName, $unwantedArray);
        $str = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $str); // Zamienia wszystko poza literami, cyframi, kropką, myślnikiem i podkreślnikiem na podkreślnik

        return $str;
    }

    /**
     * Display the user's job applications and related information.
     */
    public function showApplications(Request $request)
    {
        $userApplications = JobApplications::with(['jobPost.company', 'jobPost', 'user'])
                            ->where('user_id', Auth::id())
                            ->get();

        return response()->json([
            'applications' => $userApplications
        ]);
    }

}
