jQuery(document).ready(function($) {
    // Initialize tooltips if using Bootstrap
    if (typeof $().tooltip === 'function') {
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Handle plugin installation
    $(document).on('click', '.install-plugin', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var $loaderWrapper = $button.siblings('.loader-wrapper');
        var $loaderBar = $loaderWrapper.find('.loader-bar');
        var $progressSteps = $button.siblings('.progress-steps');
        var $successIcon = $button.find('.success-icon');
        var pluginSlug = $button.data('plugin-slug');
        var originalText = $button.text();

        // Reset and show progress elements
        $loaderWrapper.show();
        $progressSteps.show();
        $button.prop('disabled', true);
        $successIcon.hide();

        // Update progress bar and step
        function updateProgress(step, progress) {
            $loaderBar.css('width', progress + '%');
            $progressSteps.find('.step').removeClass('active');
            $progressSteps.find('.step[data-step="' + step + '"]').addClass('active');
        }

        // Handle errors
        function handleError(message) {
            $button.text(silent_installer_vars.error_text || 'Installation Failed')
                  .prop('disabled', false);
            $loaderWrapper.fadeOut(300);
            $progressSteps.fadeOut(300);

            console.error('Installation Error:', message);

            setTimeout(function() {
                $button.text(originalText);
            }, 3000);
        }

        // Complete installation
        function completeInstallation(message) {
            $button.text(message);
            $successIcon.show().addClass('fade-in');
            $loaderWrapper.fadeOut(300);
            $progressSteps.fadeOut(300);

            setTimeout(function() {
                $button.prop('disabled', true);
            }, 2000);
        }

        // Start installation process
        updateProgress('check', 25);

        // Check plugin status
        $.ajax({
            url: silent_installer_vars.ajaxurl,
            type: 'POST',
            data: {
                action: 'check_plugin_status',
                plugin_slug: pluginSlug,
                nonce: silent_installer_vars.nonce
            },
            success: function(response) {
                if (!response.success) {
                    handleError(response.data.message);
                    return;
                }

                if (response.data.installed && response.data.active) {
                    completeInstallation(silent_installer_vars.already_installed || 'Already Installed & Active');
                    return;
                }

                // Proceed with installation
                updateProgress('download', 50);
                
                $.ajax({
                    url: silent_installer_vars.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'silent_install_plugin',
                        plugin_slug: pluginSlug,
                        nonce: silent_installer_vars.nonce
                    },
                    success: function(response) {
                        if (!response.success) {
                            handleError(response.data.message);
                            return;
                        }

                        // Show installation progress
                        updateProgress('install', 75);
                        
                        setTimeout(function() {
                            updateProgress('activate', 100);
                            
                            setTimeout(function() {
                                completeInstallation(silent_installer_vars.activated_text || 'Installed & Activated!');
                                
                                // Trigger custom event for successful installation
                                $button.trigger('plugin_installation_complete', [pluginSlug, response.data]);
                            }, 500);
                        }, 500);
                    },
                    error: function(xhr, status, error) {
                        handleError(error);
                    }
                });
            },
            error: function(xhr, status, error) {
                handleError(error);
            }
        });
    });

    // Custom event handler for installation complete
    $(document).on('plugin_installation_complete', function(event, pluginSlug, data) {
        console.log('Plugin installation completed:', pluginSlug, data);
        // You can add custom functionality here
    });
});