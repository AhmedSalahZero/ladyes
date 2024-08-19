@props([
	'indexRoute'
])
 <div class="form-actions">
                                                    <a href="{{$indexRoute}}" type="button" class="btn btn-warning block-page"
                                                            {{-- onclick="history.back();" --}}
															>
                                                        <i class="ft-x"></i> {{__('Back')}}
                                                    </a>
                                                    <button type="submit" class="js-save-by-ajax btn btn-primary block-page">
                                                        <i class="la la-check-square-o"></i> {{__('Save And Back')}}
                                                    </button>
                                                    <button type="submit" name="save" value="1"
                                                            class="js-save-by-ajax btn btn-primary block-page">
                                                        <i class="la la-check-square-o"></i> {{__('Save')}}
                                                    </button>
                                                </div>
