<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\PostalCode\Contracts;

use Throwable;

/**
 * Base interface for all postal code-related exceptions.
 *
 * This interface provides a common type for all exceptions thrown by the
 * postal code validation and formatting library. Consumers can catch this
 * interface to handle all library-specific exceptions uniformly, regardless
 * of whether they are validation failures or unsupported country errors.
 *
 * @author Brian Faust <brian@cline.sh>
 */
interface PostalCodeException extends Throwable {}
