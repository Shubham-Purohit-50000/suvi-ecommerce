@extends('backend.layouts.app')
@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('About Us Page Information') }}</h1>
        </div>
    </div>
</div>
<div class="card">
    <ul class="nav nav-tabs nav-fill language-bar">
        @foreach (get_all_active_language() as $key => $language)
            <li class="nav-item">
                <a class="nav-link text-reset @if ($language->code == $lang) active @endif py-3" href="{{ route('custom-pages.edit', ['id'=>$page->slug, 'lang'=> $language->code] ) }}">
                    <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                    <span>{{$language->name}}</span>
                </a>
            </li>
        @endforeach
    </ul>

    <form class="p-4" action="{{ route('about-page.update', $page->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="lang" value="{{ $lang }}">

        <div class="card-header px-0">
            <h6 class="fw-600 mb-0">{{ translate('Page Content') }}</h6>
        </div>
        <div class="card-body px-0">
            @php
                $content = json_decode($page->content);
            @endphp
            
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="name">{{translate('Title')}} <span class="text-danger">*</span> <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="{{translate('Title')}}" name="title" value="{{ $page->getTranslation('title',$lang) }}" required>
                </div>
            </div>
            
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="slug">{{translate('Link')}} <span class="text-danger">*</span></label>
                <div class="col-sm-10">
                    <div class="input-group d-block d-md-flex">
                        @if($page->type == 'custom_page')
                            <div class="input-group-prepend"><span class="input-group-text flex-grow-1">{{ route('home') }}/</span></div>
                            <input type="text" class="form-control w-100 w-md-auto" placeholder="{{ translate('Slug') }}" name="slug" value="{{ $page->slug }}" disabled>
                        @else
                            <input class="form-control w-100 w-md-auto" value="{{ route('home') }}/{{ $page->slug }}" disabled>
                        @endif
                    </div>
                    <small class="form-text text-muted">{{ translate('Use character, number, hypen only') }}</small>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Mission Image')}}</label>
                <div class="col-sm-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ $content->mission_image ? translate('Change File') : translate('Choose File') }}</div>
                        <input type="hidden" name="mission_image" class="selected-files" value="{{ $content->mission_image ?? '' }}">
                    </div>
                    <div class="file-preview">
                        @if($content->mission_image)
                            <div class="d-flex justify-content-start mt-2">
                                <div class="img-fit img-fit-xs" style="background-image:url('{{ uploaded_asset($content->mission_image) }}')"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Mission Content')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <textarea class="aiz-text-editor form-control" name="mission_content" placeholder="{{translate('Our Mission content')}}">{{ $content->mission_content ?? '' }}</textarea>
                </div>
            </div>

            <!-- Vision Section -->
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Vision Image')}}</label>
                <div class="col-sm-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ $content->vision_image ? translate('Change File') : translate('Choose File') }}</div>
                        <input type="hidden" name="vision_image" class="selected-files" value="{{ $content->vision_image ?? '' }}">
                    </div>
                    <div class="file-preview">
                        @if($content->vision_image)
                            <div class="d-flex justify-content-start mt-2">
                                <div class="img-fit img-fit-xs" style="background-image:url('{{ uploaded_asset($content->vision_image) }}')"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Vision Content')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <textarea class="aiz-text-editor form-control" name="vision_content" placeholder="{{translate('Our Vision content')}}">{{ $content->vision_content ?? '' }}</textarea>
                </div>
            </div>

            <!-- Why Choose Section -->
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Why Choose Title')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="why_choose_title" placeholder="{{translate('Why Choose Title')}}" value="{{ $content->why_choose_title ?? '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Why Choose Description')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="why_choose_description" placeholder="{{translate('Why Choose Description')}}">{{ $content->why_choose_description ?? '' }}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Why Choose Items')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <div class="repeatable-fields">
                        @if(isset($content->why_choose_items) && count($content->why_choose_items) > 0)
                            @foreach($content->why_choose_items as $index => $item)
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="why_choose_items[{{ $index }}][title]" placeholder="{{translate('Title')}}" value="{{ $item->title ?? '' }}">
                                    </div>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="why_choose_items[{{ $index }}][description]" placeholder="{{translate('Description')}}">{{ $item->description ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-2">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-primary add-repeatable"><i class="las la-plus"></i></button>
                                        @else
                                            <button type="button" class="btn btn-danger remove-repeatable"><i class="las la-minus"></i></button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="why_choose_items[0][title]" placeholder="{{translate('Title')}}">
                                </div>
                                <div class="col-md-6">
                                    <textarea class="form-control" name="why_choose_items[0][description]" placeholder="{{translate('Description')}}"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary add-repeatable"><i class="las la-plus"></i></button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Banner Image -->
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Banner Image')}}</label>
                <div class="col-sm-10">
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ $content->banner_image ? translate('Change File') : translate('Choose File') }}</div>
                        <input type="hidden" name="banner_image" class="selected-files" value="{{ $content->banner_image ?? '' }}">
                    </div>
                    <div class="file-preview">
                        @if($content->banner_image)
                            <div class="d-flex justify-content-start mt-2">
                                <div class="img-fit img-fit-xs" style="background-image:url('{{ uploaded_asset($content->banner_image) }}')"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Business Steps -->
            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Business Steps Title')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="business_steps_title" placeholder="{{translate('Business Steps Title')}}" value="{{ $content->business_steps_title ?? '' }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label">{{translate('Business Steps')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <div class="repeatable-fields">
                        @if(isset($content->business_steps) && count($content->business_steps) > 0)
                            @foreach($content->business_steps as $index => $step)
                                <div class="row mb-3">
                                    <div class="col-md-1">
                                        <input type="text" class="form-control" name="business_steps[{{ $index }}][number]" placeholder="{{translate('No.')}}" value="{{ $step->number ?? '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="business_steps[{{ $index }}][title]" placeholder="{{translate('Title')}}" value="{{ $step->title ?? '' }}">
                                    </div>
                                    <div class="col-md-5">
                                        <textarea class="form-control" name="business_steps[{{ $index }}][description]" placeholder="{{translate('Description')}}">{{ $step->description ?? '' }}</textarea>
                                    </div>
                                    <div class="col-md-2">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-primary add-repeatable"><i class="las la-plus"></i></button>
                                        @else
                                            <button type="button" class="btn btn-danger remove-repeatable"><i class="las la-minus"></i></button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row mb-3">
                                <div class="col-md-1">
                                    <input type="text" class="form-control" name="business_steps[0][number]" placeholder="{{translate('No.')}}">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="business_steps[0][title]" placeholder="{{translate('Title')}}">
                                </div>
                                <div class="col-md-5">
                                    <textarea class="form-control" name="business_steps[0][description]" placeholder="{{translate('Description')}}"></textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary add-repeatable"><i class="las la-plus"></i></button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="name">{{translate('Main Content')}} <i class="las la-language text-danger" title="{{translate('Translatable')}}"></i></label>
                <div class="col-sm-10">
                    <textarea
                        class="aiz-text-editor form-control"
                        data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                        placeholder="Content.."
                        data-min-height="300"
                        name="main_content"
                    >{{ $content->main_content ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="card-header px-0">
            <h6 class="fw-600 mb-0">{{ translate('Seo Fields') }}</h6>
        </div>
        <div class="card-body px-0">
            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="meta_title">{{translate('Meta Title')}}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ $page->meta_title }}">
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="meta_description">{{translate('Meta Description')}}</label>
                <div class="col-sm-10">
                    <textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{!! $page->meta_description !!}</textarea>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="keywords">{{translate('Keywords')}}</label>
                <div class="col-sm-10">
                    <textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="keywords">{!! $page->keywords !!}</textarea>
                    <small class="text-muted">{{ translate('Separate with coma') }}</small>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-from-label" for="meta_image">{{translate('Meta Image')}}</label>
                <div class="col-sm-10">
                    <div class="input-group " data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ $page->meta_image ? translate('Change File') : translate('Choose File') }}</div>
                        <input type="hidden" name="meta_image" class="selected-files" value="{{ $page->meta_image }}">
                    </div>
                    <div class="file-preview">
                        @if($page->meta_image)
                            <div class="d-flex justify-content-start mt-2">
                                <div class="img-fit img-fit-xs" style="background-image:url('{{ uploaded_asset($page->meta_image) }}')"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">{{ translate('Update Page') }}</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Add repeatable fields for Why Choose items
        $(document).on('click', '.add-repeatable', function() {
            var container = $(this).closest('.repeatable-fields');
            var index = container.find('.row').length;
            
            // Check if we're adding Why Choose items or Business Steps
            if ($(this).closest('.row').find('input[name^="why_choose_items"]').length > 0) {
                var fieldHtml = `
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="why_choose_items[${index}][title]" placeholder="{{translate('Title')}}">
                        </div>
                        <div class="col-md-6">
                            <textarea class="form-control" name="why_choose_items[${index}][description]" placeholder="{{translate('Description')}}"></textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-repeatable"><i class="las la-minus"></i></button>
                        </div>
                    </div>
                `;
            } else {
                // Business Steps fields
                var fieldHtml = `
                    <div class="row mb-3">
                        <div class="col-md-1">
                            <input type="text" class="form-control" name="business_steps[${index}][number]" placeholder="{{translate('No.')}}">
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" name="business_steps[${index}][title]" placeholder="{{translate('Title')}}">
                        </div>
                        <div class="col-md-5">
                            <textarea class="form-control" name="business_steps[${index}][description]" placeholder="{{translate('Description')}}"></textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-repeatable"><i class="las la-minus"></i></button>
                        </div>
                    </div>
                `;
            }
            
            container.append(fieldHtml);
        });

        // Remove repeatable fields
        $(document).on('click', '.remove-repeatable', function() {
            $(this).closest('.row').remove();
        });
    });
</script>
@endsection