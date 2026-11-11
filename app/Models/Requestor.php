<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requestor extends Model
{
    //
    protected $fillable = [
        'email',
        'f_name',
        'l_name',
        'middle',
        'department',
        'request_id',
        // Add any other columns that belong to the 'socmed_requests' table here
    ];

    public function socmed()
    {
        return $this->hasOne(Socmed_request_information::class, 'request_id','request_id');
    }

    public function photo()
    {
        return $this->hasOne(Photo_vid_request_informations::class, 'request_id','request_id');
    }

    public function publishing()
    {
        return $this->hasOne(Publishing_request_informaitons::class, 'request_id','request_id');
    }

    /**
     * Requestors have no dedicated name column, but the video / uzpost / uzpr
     * dashboards read $req->fullname. Expose it as an accessor so those views
     * work without being rewritten.
     */
    public function getFullnameAttribute(): string
    {
        return trim(preg_replace('/\s+/', ' ', ($this->f_name ?? '').' '.($this->l_name ?? '')));
    }

    /**
     * Some dashboards read $req->information for the child row regardless of
     * whether it is a publishing or a photo/video request.
     */
    public function getInformationAttribute()
    {
        return $this->publishing ?? $this->photo ?? $this->socmed;
    }

    /**
     * Alias of information(), for with(['information']) eager loading.
     */
    public function information()
    {
        return $this->publishing();
    }

    /**
     * Which notification type this request belongs to, derived from the child
     * row. Used to build the deep link back to the right dashboard.
     */
    public function notificationType(): ?string
    {
        if ($this->publishing) {
            return \App\Support\NotificationType::forPublishingSpecification($this->publishing->specification);
        }

        if ($this->photo) {
            return \App\Support\NotificationType::forPhotoCategory($this->photo->category);
        }

        // Without this branch a social-media request resolved to null and was
        // silently dropped from the feed entirely.
        if ($this->socmed) {
            return 'socmed';
        }

        return null;
    }
}
