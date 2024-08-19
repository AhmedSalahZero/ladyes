<tr data-id="{{ $model->id }}" class="deleteable-row">
                                                <td class="text-center">{{$model->id}}</td>
                                                <td class="text-center">{{$model->getClientName()}}</td>
                                                <td class="text-center">{{$model->getDriverName()}}</td>
                                                <td class="text-center">{{$model->getCancelledByFormatted()}}</td>
                                                <td class="text-center">{{$model->getCancellationReasonFormatted()}}</td>
                                                <td class="text-center">{{$model->getFromAddress()}}</td>
                                                <td class="text-center">{{$model->getToAddress()}}</td>
                                                <td class="text-center">{{$model->getCreatedAtFormatted()}}</td>
                                                <td>
                                                    @php
                                                    $driver = $model->driver ;
                                                    $client = $model->client ;
                                                    @endphp

                                              @include('admin.travels.user-details',['travelType'=>'cancelled'])

                                                </td>
                                            </tr>
